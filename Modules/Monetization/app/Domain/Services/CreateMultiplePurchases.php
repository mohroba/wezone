<?php

namespace Modules\Monetization\Domain\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Monetization\Domain\DTO\CreatePurchaseDTO;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;

class CreateMultiplePurchases
{
    public function __construct(
        private readonly CreatePurchase $createPurchase,
        private readonly PayWithWallet $payWithWallet,
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $planPayloads
     * @return array{purchases: Collection<int, AdPlanPurchase>, payments: Collection}
     */
    public function handle(
        int $adId,
        int $advertisableTypeId,
        ?int $adCategoryId,
        int $userId,
        ?string $baseCorrelationId,
        ?string $baseIdempotencyKey,
        array $planPayloads
    ): array {
        $purchases = collect();
        $payments = collect();

        DB::transaction(function () use (
            $planPayloads,
            $adId,
            $advertisableTypeId,
            $adCategoryId,
            $userId,
            $baseCorrelationId,
            $baseIdempotencyKey,
            &$purchases,
            &$payments
        ): void {
            foreach ($planPayloads as $index => $planPayload) {
                $idempotencyKey = $planPayload['idempotency_key']
                    ?? ($baseIdempotencyKey ? $baseIdempotencyKey.'-'.$index : Str::uuid()->toString());
                $correlationId = $baseCorrelationId ?? Str::uuid()->toString();

                $purchase = ($this->createPurchase)(new CreatePurchaseDTO(
                    adId: $adId,
                    planId: array_key_exists('plan_id', $planPayload) ? ($planPayload['plan_id'] ?? null) : null,
                    planSlug: $planPayload['plan_slug'] ?? null,
                    userId: $userId,
                    gateway: $planPayload['gateway'] ?? null,
                    correlationId: $correlationId,
                    idempotencyKey: $idempotencyKey,
                    advertisableTypeId: $advertisableTypeId,
                    adCategoryId: $adCategoryId,
                    discountCode: $planPayload['discount_code'] ?? null,
                    payWithWallet: (bool) ($planPayload['pay_with_wallet'] ?? false),
                ));

                $purchases->push($purchase->load('plan'));

                if ($purchase->meta['pay_with_wallet'] ?? false) {
                    $payments->push(($this->payWithWallet)($purchase));
                }
            }
        });

        return [
            'purchases' => $purchases,
            'payments' => $payments,
        ];
    }
}
