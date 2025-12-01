<?php

namespace Modules\Monetization\Domain\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\Monetization\Domain\Contracts\Repositories\DiscountRedemptionRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\DTO\PurchasePrice;

class DiscountRedemptionService
{
    public function __construct(private readonly DiscountRedemptionRepository $redemptionRepository)
    {
    }

    public function reserveAndLog(
        PlanPriceOverride $priceRule,
        ?DiscountCode $discountCode,
        AdPlanPurchase $purchase,
        PurchasePrice $pricing,
        ?int $userId,
    ): void {
        DB::transaction(function () use ($priceRule, $discountCode, $purchase, $pricing, $userId): void {
            $lockedRule = PlanPriceOverride::query()->lockForUpdate()->find($priceRule->getKey());

            if ($lockedRule && $lockedRule->usage_cap !== null && $lockedRule->usage_count >= $lockedRule->usage_cap) {
                throw new \RuntimeException('Price rule usage cap exceeded.');
            }

            if ($lockedRule) {
                $lockedRule->increment('usage_count');
            }

            if ($discountCode) {
                $lockedCode = DiscountCode::query()->lockForUpdate()->find($discountCode->getKey());

                if ($lockedCode && $lockedCode->usage_cap !== null && $lockedCode->usage_count >= $lockedCode->usage_cap) {
                    throw new \RuntimeException('Discount code cap exceeded.');
                }

                if ($lockedCode && $lockedCode->per_user_cap !== null && $userId !== null) {
                    $userRedemptions = $lockedCode->redemptions()->where('user_id', $userId)->lockForUpdate()->count();

                    if ($userRedemptions >= $lockedCode->per_user_cap) {
                        throw new \RuntimeException('Discount code per-user cap exceeded.');
                    }
                }

                if ($lockedCode) {
                    $lockedCode->increment('usage_count');
                }
            }

            $this->redemptionRepository->create([
                'discount_code_id' => $discountCode?->getKey(),
                'plan_price_override_id' => $priceRule->getKey(),
                'ad_plan_purchase_id' => $purchase->getKey(),
                'user_id' => $userId,
                'amount_before' => $pricing->listPrice,
                'amount_after' => $pricing->discountedPrice,
                'discount_amount' => $pricing->listPrice - $pricing->discountedPrice,
                'redeemed_at' => now(),
                'meta' => [
                    'discount_code' => $pricing->discountCode,
                    'stackable' => $priceRule->is_stackable,
                ],
            ]);

            Log::info('Discount redeemed', [
                'purchase_id' => $purchase->getKey(),
                'price_rule_id' => $priceRule->getKey(),
                'discount_code_id' => $discountCode?->getKey(),
                'discount_code' => $pricing->discountCode,
                'user_id' => $userId,
            ]);
        });
    }
}
