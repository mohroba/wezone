<?php

namespace Modules\Monetization\Domain\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Events\PaymentInitiated;
use Modules\Monetization\Domain\ValueObjects\Money;

class InitiateAggregatedPayment
{
    public function __construct(
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentGatewayRegistry $gatewayRegistry,
    ) {
    }

    /**
     * @param Collection<int, AdPlanPurchase>|array<int, AdPlanPurchase> $purchases
     */
    public function handle(
        Collection|array $purchases,
        int $userId,
        string $gateway,
        ?string $idempotencyKey,
        ?string $correlationId,
    ): Payment {
        $collection = $purchases instanceof Collection ? $purchases : collect($purchases);

        if ($collection->isEmpty()) {
            throw new InvalidArgumentException('No purchases supplied for aggregated payment.');
        }

        $gatewayInstance = $this->gatewayRegistry->resolve($gateway);
        /** @var AdPlanPurchase $referencePurchase */
        $referencePurchase = $collection->first();
        $collection->each(function (AdPlanPurchase $purchase) use ($referencePurchase): void {
            if ($purchase->currency !== $referencePurchase->currency) {
                throw new InvalidArgumentException('Bulk purchases must share the same currency.');
            }
        });

        $totalAmount = $collection->sum(fn (AdPlanPurchase $purchase) => $purchase->effectiveAmount());

        return DB::transaction(function () use (
            $collection,
            $gatewayInstance,
            $totalAmount,
            $referencePurchase,
            $userId,
            $idempotencyKey,
            $correlationId,
        ): Payment {
            if ($idempotencyKey) {
                $existing = $this->paymentRepository->findByIdempotencyKey($idempotencyKey, $gatewayInstance->getName());
                if ($existing) {
                    return $existing;
                }
            }

            $referencePurchase->loadMissing('plan');

            $payment = $this->paymentRepository->create([
                'user_id' => $userId,
                'payable_type' => $referencePurchase::class,
                'payable_id' => $referencePurchase->getKey(),
                'amount' => $totalAmount,
                'currency' => $referencePurchase->currency,
                'gateway' => $gatewayInstance->getName(),
                'status' => 'pending',
                'correlation_id' => $correlationId,
                'idempotency_key' => $idempotencyKey,
                'meta' => [
                    'purchase_ids' => $collection->pluck('id')->values()->all(),
                ],
            ]);

            $collection->each(function (AdPlanPurchase $purchase) use ($gatewayInstance): void {
                $this->purchaseRepository->update($purchase, [
                    'payment_status' => 'pending',
                    'payment_gateway' => $gatewayInstance->getName(),
                ]);
            });

            $result = $gatewayInstance->initiate(new GatewayInitiationData(
                purchase: $referencePurchase,
                plan: $referencePurchase->plan,
                money: Money::fromFloat($payment->amount, $referencePurchase->currency),
                callbackUrl: Config::get('monetization.gateways.'.$gatewayInstance->getName().'.callback_url'),
            ));

            Event::dispatch(new PaymentInitiated(purchase: $referencePurchase, payment: $result));

            return $result;
        });
    }
}
