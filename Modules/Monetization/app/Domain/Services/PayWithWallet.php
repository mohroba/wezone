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
        if ($purchase->payment_status !== 'draft') {
            throw new InvalidArgumentException('Only draft purchases can be paid with wallet.');
        }

        return DB::transaction(function () use ($purchase): Payment {
            $wallet = $this->walletRepository->findOrCreateForUser($purchase->user_id, $purchase->currency);

            if ($wallet->balance < $purchase->amount) {
                throw new InvalidArgumentException('Insufficient wallet balance.');
            }

            $before = $wallet->balance;
            $this->walletRepository->updateBalance($wallet, -$purchase->amount);
            $after = $wallet->balance;

            $transaction = $this->walletRepository->createTransaction($wallet, [
                'type' => WalletTransaction::TYPE_AD_PURCHASE,
                'amount' => $purchase->amount,
                'before_balance' => $before,
                'after_balance' => $after,
                'reference_type' => $purchase::class,
                'reference_id' => $purchase->getKey(),
                'description' => 'Plan purchase',
            ]);

            $payment = $this->paymentRepository->create([
                'user_id' => $purchase->user_id,
                'payable_type' => $purchase::class,
                'payable_id' => $purchase->getKey(),
                'amount' => $purchase->amount,
                'currency' => $purchase->currency,
                'gateway' => 'wallet',
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $this->activationService->activate($purchase->fresh('plan'));

            Event::dispatch(new PaymentSucceeded(payment: $payment, purchase: $purchase));

            return $payment;
        });
    }
}
