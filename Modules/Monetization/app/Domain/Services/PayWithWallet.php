<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Entities\Wallet;
use Modules\Monetization\Domain\Entities\WalletTransaction;
use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class PayWithWallet
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly PaymentRepository $paymentRepository,
        private readonly PurchaseActivationService $activationService,
    ) {
    }

    public function __invoke(AdPlanPurchase $purchase): Payment
    {
        if ($purchase->exists) {
            $purchase->refresh();
        }

        if ($purchase->payment_status !== 'draft') {
            throw new InvalidArgumentException('Only draft purchases can be paid with wallet.');
        }

        $chargeAmount = $purchase->effectiveAmount();

        $existing = $purchase->idempotency_key
            ? $this->paymentRepository->findByIdempotencyKey($purchase->idempotency_key, 'wallet')
            : null;

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($purchase): Payment {
            $wallet = $this->walletRepository->findOrCreateForUser($purchase->user_id, $purchase->currency);

            $chargeAmount = $purchase->effectiveAmount();

            if ($wallet->balance < $chargeAmount) {
                throw new InvalidArgumentException('Insufficient wallet balance.');
            }

            $before = $wallet->balance;
            $this->walletRepository->updateBalance($wallet, -$chargeAmount);
            $after = $wallet->balance;

            $transaction = $this->walletRepository->createTransaction($wallet, [
                'type' => WalletTransaction::TYPE_AD_PURCHASE,
                'amount' => $chargeAmount,
                'before_balance' => $before,
                'after_balance' => $after,
                'reference_type' => $purchase::class,
                'reference_id' => $purchase->getKey(),
                'description' => 'Plan purchase',
                'meta' => array_filter([
                    'discount_redemption' => $purchase->meta['discount_redemption'] ?? null,
                    'pricing' => $purchase->meta['pricing'] ?? null,
                    'charged_amount' => $chargeAmount,
                ]),
            ]);

            $payment = $this->paymentRepository->create([
                'user_id' => $purchase->user_id,
                'payable_type' => $purchase::class,
                'payable_id' => $purchase->getKey(),
                'amount' => $chargeAmount,
                'currency' => $purchase->currency,
                'gateway' => 'wallet',
                'status' => 'paid',
                'paid_at' => now(),
                'correlation_id' => $purchase->correlation_id,
                'idempotency_key' => $purchase->idempotency_key,
                'meta' => array_filter([
                    'pricing' => $purchase->meta['pricing'] ?? null,
                    'discount_redemption' => $purchase->meta['discount_redemption'] ?? null,
                    'wallet_transaction_id' => $transaction->getKey(),
                    'charged_amount' => $chargeAmount,
                ]),
            ]);

            $this->activationService->activate($purchase->fresh('plan'));

            Event::dispatch(new PaymentSucceeded(payment: $payment, purchase: $purchase));

            return $payment;
        });
    }
}
