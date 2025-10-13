<?php

namespace Modules\Monetization\Http\Controllers;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Services\ApplyBump;
use Modules\Monetization\Domain\Services\CreatePurchase;
use Modules\Monetization\Domain\Services\PayWithWallet;
use Modules\Monetization\Http\Requests\BumpRequest;
use Modules\Monetization\Http\Requests\CreatePurchaseRequest;
use Modules\Monetization\Http\Resources\PaymentResource;
use Modules\Monetization\Http\Resources\PurchaseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PurchaseController
{
    public function __construct(
        private readonly CreatePurchase $createPurchase,
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PayWithWallet $payWithWallet,
        private readonly ApplyBump $applyBump,
    ) {
    }

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

    public function show(AdPlanPurchase $purchase): PurchaseResource
    {
        return new PurchaseResource($purchase->load('plan'));
    }

    public function bump(BumpRequest $request, AdPlanPurchase $purchase): PurchaseResource
    {
        $updated = ($this->applyBump)($purchase);

        return new PurchaseResource($updated->load('plan'));
    }
}
