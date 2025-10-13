<?php

namespace Modules\Monetization\Http\Controllers;

use Modules\Monetization\Domain\DTO\InitiatePaymentDTO;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Services\InitiatePayment;
use Modules\Monetization\Domain\Services\VerifyPaymentCallback;
use Modules\Monetization\Http\Requests\InitiatePaymentRequest;
use Modules\Monetization\Http\Requests\VerifyCallbackRequest;
use Modules\Monetization\Http\Resources\PaymentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PaymentController
{
    public function __construct(
        private readonly InitiatePayment $initiatePayment,
        private readonly VerifyPaymentCallback $verifyPaymentCallback,
    ) {
    }

    public function initiate(InitiatePaymentRequest $request, AdPlanPurchase $purchase): PaymentResource
    {
        $payment = ($this->initiatePayment)(new InitiatePaymentDTO(
            purchaseId: $purchase->getKey(),
            userId: $request->user()->getKey(),
            gateway: $request->input('gateway', $purchase->payment_gateway),
            idempotencyKey: $request->header('X-Idempotency-Key'),
            correlationId: $request->header('X-Correlation-Id') ?? Str::uuid()->toString(),
        ));

        return new PaymentResource($payment);
    }

    public function verify(VerifyCallbackRequest $request): PaymentResource
    {
        $payment = ($this->verifyPaymentCallback)(new VerifyPaymentDTO(
            gateway: $request->input('gateway'),
            payload: $request->input('payload'),
            idempotencyKey: $request->header('X-Idempotency-Key'),
            correlationId: $request->header('X-Correlation-Id'),
        ));

        return new PaymentResource($payment);
    }
}
