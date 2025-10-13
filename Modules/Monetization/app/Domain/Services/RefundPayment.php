<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\RefundPaymentDTO;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Events\RefundProcessed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class RefundPayment
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly PaymentGatewayRegistry $gatewayRegistry,
    ) {
    }

    public function __invoke(RefundPaymentDTO $dto): Payment
    {
        $payment = $this->paymentRepository->findById($dto->paymentId);
        if (! $payment) {
            throw new InvalidArgumentException('Payment not found.');
        }

        return DB::transaction(function () use ($payment, $dto): Payment {
            $gateway = $this->gatewayRegistry->resolve($payment->gateway);
            $refunded = $gateway->refund(new GatewayRefundData($payment));

            Event::dispatch(new RefundProcessed(payment: $refunded, purchase: $refunded->payable));

            return $refunded;
        });
    }
}
