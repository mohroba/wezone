<?php

namespace Modules\Monetization\Http\Controllers;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Services\ApplyBump;
use Modules\Monetization\Domain\Services\CreatePurchase;
use Modules\Monetization\Domain\Services\CreateMultiplePurchases;
use Modules\Monetization\Domain\Services\PayWithWallet;
use Modules\Monetization\Http\Requests\BumpRequest;
use Modules\Monetization\Http\Requests\BulkCreatePurchaseRequest;
use Modules\Monetization\Http\Requests\CreatePurchaseRequest;
use Modules\Monetization\Http\Resources\PaymentResource;
use Modules\Monetization\Http\Resources\PurchaseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Monetization
 *
 * Create, inspect, and manage ad plan purchases.
 */
class PurchaseController
{
    public function __construct(
        private readonly CreatePurchase $createPurchase,
        private readonly CreateMultiplePurchases $createMultiplePurchases,
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PayWithWallet $payWithWallet,
        private readonly ApplyBump $applyBump,
    ) {
    }

    /**
     * Create ad plan purchase
     *
     * @group Monetization
     *
     * Start a new purchase for an ad promotion plan. Depending on the payload, the purchase may be automatically paid with the
     * wallet.
     *
     * @bodyParam ad_id int required Identifier of the ad to promote. Example: 41
     * @bodyParam plan_id int Optional plan identifier when selecting directly by ID. Example: 5
     * @bodyParam plan_slug string Optional plan slug alternative to `plan_id`. Example: premium-weekly
     * @bodyParam gateway string Preferred payment gateway key. Example: payping
     * @bodyParam advertisable_type_id int required Identifies the advertisable type for contextual pricing. Example: 2
     * @bodyParam ad_category_id int Optional category used to target price rules. Example: 14
     * @bodyParam discount_code string Optional discount code to unlock contextual promotions. Example: SPRING24
     * @bodyParam pay_with_wallet boolean Whether to use the wallet balance immediately. Example: false
     *
     * @responseField data.id integer Unique identifier of the created purchase.
     * @responseField payment object|null Included when the wallet covers the full purchase amount.
     */
    public function store(CreatePurchaseRequest $request): PurchaseResource
    {
        $idempotencyKey = $request->header('X-Idempotency-Key') ?? Str::uuid()->toString();
        $correlationId = $request->header('X-Correlation-Id') ?? Str::uuid()->toString();

        $purchase = ($this->createPurchase)(new CreatePurchaseDTO(
            adId: $request->integer('ad_id'),
            planId: $request->has('plan_id') ? $request->integer('plan_id') : null,
            planSlug: $request->input('plan_slug'),
            userId: $request->user()->getKey(),
            gateway: $request->input('gateway'),
            correlationId: $correlationId,
            idempotencyKey: $idempotencyKey,
            advertisableTypeId: $request->integer('advertisable_type_id'),
            adCategoryId: $request->has('ad_category_id') ? $request->integer('ad_category_id') : null,
            discountCode: $request->input('discount_code'),
            payWithWallet: (bool) $request->boolean('pay_with_wallet'),
        ));

        if ($purchase->meta['pay_with_wallet'] ?? false) {
            $payment = ($this->payWithWallet)($purchase);
            $purchase->load('plan');

            return (new PurchaseResource($purchase))->additional([
                'payment' => new PaymentResource($payment),
            ]);
        }

        return new PurchaseResource($purchase->load('plan'));
    }

    /**
     * Create multiple ad plan purchases
     *
     * @group Monetization
     *
     * Start multiple purchases for an ad in a single request. Each plan payload can optionally use the wallet immediately.
     *
     * @responseField data array List of created purchases.
     * @responseField payments array|null Wallet payments executed for eligible purchases.
     */
    public function storeMany(BulkCreatePurchaseRequest $request): JsonResponse
    {
        $correlationId = $request->header('X-Correlation-Id') ?? Str::uuid()->toString();
        $idempotencyKey = $request->header('X-Idempotency-Key');

        $result = $this->createMultiplePurchases->handle(
            adId: $request->integer('ad_id'),
            advertisableTypeId: $request->integer('advertisable_type_id'),
            adCategoryId: $request->has('ad_category_id') ? $request->integer('ad_category_id') : null,
            userId: $request->user()->getKey(),
            baseCorrelationId: $correlationId,
            baseIdempotencyKey: $idempotencyKey,
            planPayloads: $request->input('plans', []),
        );

        return PurchaseResource::collection($result['purchases'])
            ->additional([
                'payments' => $result['payments']->isNotEmpty()
                    ? PaymentResource::collection($result['payments'])
                    : [],
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Retrieve purchase details
     *
     * @group Monetization
     *
     * @urlParam purchase integer required The purchase identifier.
     */
    public function show(AdPlanPurchase $purchase): PurchaseResource
    {
        return new PurchaseResource($purchase->load('plan'));
    }

    /**
     * Apply ad bump
     *
     * @group Monetization
     *
     * Refresh the ad associated with the purchase to the top of listings when supported by the plan.
     *
     * @urlParam purchase integer required The purchase identifier.
     * @response 200 {
     *   "data": {
     *     "id": 16,
     *     "ad_id": 42,
     *     "plan": {
     *       "id": 5,
     *       "name": "Premium Weekly",
     *       "slug": "premium-weekly",
     *       "description": "Promote the ad for seven days.",
     *       "price": 90000,
     *       "currency": "IRR",
     *       "duration_days": 7,
     *       "features": {
     *         "highlight": true,
     *         "bump": true
     *       }
     *     },
     *     "amount": 90000,
     *     "currency": "IRR",
     *     "payment_status": "paid",
     *     "payment_gateway": "payping",
     *     "starts_at": "2025-01-01T00:00:00+00:00",
     *     "ends_at": "2025-01-08T00:00:00+00:00",
     *     "meta": {},
     *     "payments": []
     *   }
     * }
     */
    public function bump(BumpRequest $request, AdPlanPurchase $purchase): PurchaseResource
    {
        $updated = ($this->applyBump)($purchase);

        return new PurchaseResource($updated->load('plan'));
    }
}
