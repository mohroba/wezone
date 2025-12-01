<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Events\PaymentFailed;
use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Modules\Monetization\Domain\Services\ApplyBump;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use RuntimeException;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Illuminate\Support\Collection;

class VerifyPaymentCallback
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PurchaseActivationService $purchaseActivationService,
        private readonly PaymentGatewayRegistry $gatewayRegistry,
        private readonly PurchaseRepository $purchaseRepository,
        private readonly ApplyBump $applyBump,
    ) {
    }

    public function __invoke(VerifyPaymentDTO $dto): Payment
    {
        $gateway = $this->gatewayRegistry->resolve($dto->gateway);
        $refId = $dto->payload['authority']
            ?? $dto->payload['id']
            ?? $dto->payload['data']['object']['id']
            ?? null;

        if (! $refId) {
            throw new InvalidArgumentException('Unable to determine payment reference from payload.');
        }

        $payment = $this->paymentRepository->findByReference($refId);
        if (! $payment) {
            throw new InvalidArgumentException('Payment reference not found.');
        }

        if ($payment->status === 'paid') {
            return $payment;
        }

        try {
            return DB::transaction(function () use ($gateway, $payment, $dto): Payment {
                $verifiedPayment = $gateway->verify(new GatewayVerificationData($payment, $dto->payload));

                $activatedPurchases = $this->activatePurchases($verifiedPayment);

                Event::dispatch(new PaymentSucceeded(payment: $verifiedPayment, purchase: $verifiedPayment->payable));

                $this->autoBumpPurchases($activatedPurchases);

                return $verifiedPayment;
            });
        } catch (RuntimeException $exception) {
            $payment->update([
                'status' => 'failed',
                'failed_at' => now(),
                'response_payload' => $dto->payload,
            ]);

            Event::dispatch(new PaymentFailed(payment: $payment));

            throw $exception;
        }
    }

    /**
     * @return Collection<int, AdPlanPurchase>
     */
    private function activatePurchases(Payment $payment): Collection
    {
        $activations = collect();

        if ($payment->payable instanceof AdPlanPurchase) {
            $activations->push($this->purchaseActivationService->activate($payment->payable));
        }

        $additionalIds = collect($payment->meta['purchase_ids'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique();

        foreach ($additionalIds as $purchaseId) {
            if ($payment->payable instanceof AdPlanPurchase && $payment->payable->getKey() === $purchaseId) {
                continue;
            }

            $purchase = $this->purchaseRepository->findById($purchaseId);
            if (! $purchase) {
                continue;
            }

            $activations->push($this->purchaseActivationService->activate($purchase));
        }

        return $activations;
    }

    /**
     * @param Collection<int, AdPlanPurchase> $purchases
     */
    private function autoBumpPurchases(Collection $purchases): void
    {
        foreach ($purchases as $purchase) {
            $planFeatures = $purchase->plan?->features ?? [];

            if (! ($planFeatures['bump']['allowance'] ?? false)) {
                continue;
            }

            try {
                ($this->applyBump)($purchase);
            } catch (\Throwable) {
                // Ignore bump errors to avoid interrupting payment verification
            }
        }
    }
}
