<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\InitiatePaymentDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Events\PaymentInitiated;
use Modules\Monetization\Domain\ValueObjects\Money;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class InitiatePayment
{
    public function __construct(
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PlanRepository $planRepository,
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentGatewayRegistry $gatewayRegistry,
    ) {
    }

    public function __invoke(InitiatePaymentDTO $dto): Payment
    {
        $purchase = $this->purchaseRepository->findById($dto->purchaseId);
        if (! $purchase) {
            throw new InvalidArgumentException('Purchase not found.');
        }

        if ($purchase->payment_status !== 'draft' && $purchase->payment_status !== 'pending') {
            throw new InvalidArgumentException('Purchase is not eligible for payment initiation.');
        }

        $gateway = $this->gatewayRegistry->resolve($dto->gateway ?? Config::get('monetization.default_gateway'));
        $plan = $this->planRepository->findById($purchase->plan_id);
        if (! $plan) {
            throw new InvalidArgumentException('Plan not found for purchase.');
        }

        return DB::transaction(function () use ($purchase, $gateway, $plan, $dto): Payment {
            if ($dto->idempotencyKey) {
                $existing = $this->paymentRepository->findByIdempotencyKey($dto->idempotencyKey, $gateway->getName());
                if ($existing) {
                    return $existing;
                }
            }

            $payment = $this->paymentRepository->create([
                'user_id' => $dto->userId,
                'payable_type' => $purchase::class,
                'payable_id' => $purchase->getKey(),
                'amount' => $purchase->effectiveAmount(),
                'currency' => $purchase->currency,
                'gateway' => $gateway->getName(),
                'status' => 'pending',
                'correlation_id' => $dto->correlationId,
                'idempotency_key' => $dto->idempotencyKey,
                'meta' => $this->buildPaymentMeta($purchase),
            ]);

            $purchase->payment_status = 'pending';
            $purchase->payment_gateway = $gateway->getName();
            $purchase->save();

            $result = $gateway->initiate(new GatewayInitiationData(
                purchase: $purchase,
                plan: $plan,
                money: Money::fromFloat($payment->amount, $purchase->currency),
                callbackUrl: Config::get('monetization.gateways.'.$gateway->getName().'.callback_url'),
            ));

            Event::dispatch(new PaymentInitiated(purchase: $purchase, payment: $result));

            return $result;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPaymentMeta(AdPlanPurchase $purchase): array
    {
        $pricing = $purchase->meta['pricing'] ?? [];

        return array_filter([
            'pricing' => $pricing ?: null,
            'discount_redemption' => $purchase->meta['discount_redemption'] ?? null,
            'charged_amount' => $purchase->effectiveAmount(),
        ], static fn ($value) => $value !== null);
    }
}
