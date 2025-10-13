<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Events\PaymentFailed;
use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use RuntimeException;

class VerifyPaymentCallback
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PurchaseActivationService $purchaseActivationService,
        private readonly PaymentGatewayRegistry $gatewayRegistry,
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

                $this->purchaseActivationService->activate($verifiedPayment->payable);

                Event::dispatch(new PaymentSucceeded(payment: $verifiedPayment, purchase: $verifiedPayment->payable));

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
}
