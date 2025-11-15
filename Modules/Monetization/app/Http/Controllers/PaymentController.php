<?php

namespace Modules\Monetization\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use RuntimeException;
use Modules\Ad\Models\Ad;
use Modules\Monetization\Domain\DTO\InitiatePaymentDTO;
use Modules\Monetization\Domain\DTO\VerifyPaymentDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Services\InitiatePayment;
use Modules\Monetization\Domain\Services\VerifyPaymentCallback;
use Modules\Monetization\Http\Requests\CreatePaymentRequest;
use Modules\Monetization\Http\Requests\InitiatePaymentRequest;
use Modules\Monetization\Http\Requests\ListPaymentsRequest;
use Modules\Monetization\Http\Requests\ValidatePaymentRequest;
use Modules\Monetization\Http\Requests\VerifyCallbackRequest;
use Modules\Monetization\Http\Resources\PaymentResource;

/**
 * @group Monetization
 *
 * Manage payment initiation, validation, and listing endpoints.
 */
class PaymentController
{
    public function __construct(
        private readonly InitiatePayment $initiatePayment,
        private readonly VerifyPaymentCallback $verifyPaymentCallback,
    ) {
    }

    /**
     * List payments
     *
     * @group Monetization
     *
     * Fetch a paginated list of the authenticated user's payments.
     *
     * @queryParam status string Filter by payment status. Example: paid
     * @queryParam gateway string Filter by gateway key. Example: payping
     * @queryParam ad_id integer Limit to payments linked to the specified ad. Example: 42
     * @queryParam purchase_id integer Limit to payments for a specific purchase. Example: 15
     * @queryParam per_page integer Results per page (max 100). Example: 20
     */
    public function index(ListPaymentsRequest $request): AnonymousResourceCollection
    {
        $query = Payment::query()
            ->where('user_id', $request->user()->getKey())
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->input('status')))
            ->when($request->filled('gateway'), fn ($builder) => $builder->where('gateway', $request->input('gateway')))
            ->when($request->filled('ad_id'), function ($builder) use ($request) {
                $builder->whereHasMorph('payable', [AdPlanPurchase::class], function ($relation) use ($request): void {
                    $relation->where('ad_id', $request->integer('ad_id'));
                });
            })
            ->when($request->filled('purchase_id'), function ($builder) use ($request): void {
                $builder->where('payable_id', $request->integer('purchase_id'))
                    ->where('payable_type', AdPlanPurchase::class);
            })
            ->orderByDesc('created_at');

        $perPage = (int) min($request->integer('per_page', 15), 100);

        return PaymentResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create payment
     *
     * @group Monetization
     *
     * Initialize a new payment for an existing ad plan purchase.
     *
     * @bodyParam purchase_id int required Identifier of the ad plan purchase to pay for. Example: 10
     * @bodyParam gateway string optional Override the payment gateway key. Example: zarinpal
     *
     * @responseField data.redirect_url string|null Gateway redirection URL when available.
     */
    public function store(CreatePaymentRequest $request): PaymentResource
    {
        $purchase = AdPlanPurchase::query()
            ->whereKey($request->integer('purchase_id'))
            ->where('user_id', $request->user()->getKey())
            ->first();

        if (! $purchase) {
            abort(Response::HTTP_NOT_FOUND, 'Purchase not found.');
        }

        $payment = ($this->initiatePayment)(new InitiatePaymentDTO(
            purchaseId: $purchase->getKey(),
            userId: $request->user()->getKey(),
            gateway: $this->resolveGateway(
                $request->input('gateway'),
                $purchase->payment_gateway
            ),
            idempotencyKey: $request->header('X-Idempotency-Key'),
            correlationId: $request->header('X-Correlation-Id') ?? Str::uuid()->toString(),
        ));

        return new PaymentResource($payment);
    }

    /**
     * Initiate payment for purchase
     *
     * @group Monetization
     *
     * @urlParam purchase integer required The purchase identifier.
     * @bodyParam gateway string optional Override the payment gateway key. Example: stripe
     * @responseField data.redirect_url string|null Gateway redirection URL when available.
     */
    public function initiate(InitiatePaymentRequest $request, AdPlanPurchase $purchase): PaymentResource
    {
        $payment = ($this->initiatePayment)(new InitiatePaymentDTO(
            purchaseId: $purchase->getKey(),
            userId: $request->user()->getKey(),
            gateway: $this->resolveGateway(
                $request->input('gateway'),
                $purchase->payment_gateway
            ),
            idempotencyKey: $request->header('X-Idempotency-Key'),
            correlationId: $request->header('X-Correlation-Id') ?? Str::uuid()->toString(),
        ));

        return new PaymentResource($payment);
    }

    /**
     * Validate payment
     *
     * @group Monetization
     *
     * Manually trigger validation for a payment after returning from the gateway.
     *
     * @bodyParam payload object optional Gateway callback payload. Example: {"authority": "A0001"}
     */
    public function validatePayment(ValidatePaymentRequest $request, Payment $payment): PaymentResource
    {
        if ((int) $payment->user_id !== (int) $request->user()->getKey()) {
            abort(Response::HTTP_FORBIDDEN, 'You are not allowed to validate this payment.');
        }

        $payload = $request->input('payload', []);

        if (is_array($payload) && $payment->ref_id) {
            $payload['id'] = $payload['id'] ?? $payment->ref_id;
            $payload['authority'] = $payload['authority'] ?? $payment->ref_id;
        }

        $verified = ($this->verifyPaymentCallback)(new VerifyPaymentDTO(
            gateway: $payment->gateway,
            payload: $payload,
            idempotencyKey: $request->header('X-Idempotency-Key'),
            correlationId: $request->header('X-Correlation-Id'),
        ));

        if ($verified->getKey() !== $payment->getKey()) {
            abort(Response::HTTP_CONFLICT, 'Verification resolved an unexpected payment.');
        }

        return new PaymentResource($verified);
    }

    /**
     * Verify payment callback
     *
     * @group Monetization
     *
     * Process an asynchronous callback received directly from the payment gateway.
     *
     * @bodyParam gateway string required Payment gateway key for the callback. Example: payping
     * @bodyParam payload object required Raw callback payload forwarded from the gateway. Example: {"status":"paid"}
     */
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

    /**
     * List ad payments
     *
     * @group Monetization
     *
     * Retrieve payments associated with a specific ad owned by the authenticated user.
     *
     * @queryParam per_page integer Results per page (max 100). Example: 15
     * @urlParam ad integer required The ad identifier.
     */
    public function adPayments(Request $request, Ad $ad): AnonymousResourceCollection
    {
        if ((int) $ad->user_id !== (int) $request->user()->getKey()) {
            abort(Response::HTTP_FORBIDDEN, 'You are not allowed to view payments for this ad.');
        }

        $perPage = (int) min($request->integer('per_page', 15), 100);

        $payments = $ad->payments()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return PaymentResource::collection($payments);
}

    private function resolveGateway(?string $requestedGateway, ?string $purchaseGateway): string
    {
        $requested = $this->normalizeGateway($requestedGateway);
        if ($requested !== null) {
            return $requested;
        }

        $stored = $this->normalizeGateway($purchaseGateway);
        if ($stored !== null) {
            return $stored;
        }

        $default = $this->normalizeGateway(Config::get('monetization.default_gateway'));

        if ($default !== null) {
            return $default;
        }

        throw new RuntimeException('Default payment gateway is not configured.');
    }

    private function normalizeGateway(?string $gateway): ?string
    {
        if (! is_string($gateway)) {
            return null;
        }

        $trimmed = trim($gateway);

        return $trimmed !== '' ? $trimmed : null;
    }
}
