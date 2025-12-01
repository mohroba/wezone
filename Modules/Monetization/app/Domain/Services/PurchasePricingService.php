<?php

namespace Modules\Monetization\Domain\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Modules\Monetization\Domain\DTO\PurchasePrice;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

class PurchasePricingService
{
    public function calculate(
        Plan $plan,
        int $advertisableTypeId,
        ?int $adCategoryId = null,
        ?string $discountCode = null,
        ?int $userId = null,
    ): PurchasePrice
    {
        if (! $plan->relationLoaded('priceOverrides')) {
            $plan->load('priceOverrides');
        }

        $priceRule = $this->matchPriceRule($plan, $advertisableTypeId, $adCategoryId);
        $listPrice = $priceRule?->override_price ?? $plan->price;
        $currency = $priceRule?->currency ?? $plan->currency;

        [$discountedPrice, $discountApplied] = $this->applyRuleDiscount(
            $priceRule,
            $listPrice,
            $discountCode,
            $userId,
        );

        $appliedDiscountCode = $discountApplied ? $discountCode : null;

        return new PurchasePrice(
            listPrice: $listPrice,
            discountedPrice: $discountedPrice,
            currency: $currency,
            priceRule: $priceRule,
            discountCode: $appliedDiscountCode,
            discountApplied: $discountApplied,
        );
    }

    private function matchPriceRule(Plan $plan, int $advertisableTypeId, ?int $adCategoryId): ?PlanPriceOverride
    {
        $candidates = $plan->priceOverrides
            ->where('advertisable_type_id', $advertisableTypeId)
            ->sortByDesc(function (PlanPriceOverride $rule) use ($adCategoryId): int {
                return $rule->ad_category_id && $adCategoryId && $rule->ad_category_id === $adCategoryId ? 2 : ($rule->ad_category_id === null ? 0 : 1);
            });

        return $candidates
            ->filter(function (PlanPriceOverride $rule) use ($adCategoryId): bool {
                return $rule->ad_category_id === null || $rule->ad_category_id === $adCategoryId;
            })
            ->first();
    }

    /**
     * @return array{0: float, 1: bool}
     */
    private function applyRuleDiscount(?PlanPriceOverride $rule, float $listPrice, ?string $discountCode, ?int $userId): array
    {
        if (! $rule || $rule->discount_type === 'none') {
            return [$listPrice, false];
        }

        if ($this->isDiscountInactive($rule)) {
            return [$listPrice, false];
        }

        $eligibleCodes = Arr::wrap($rule->metadata['discount_codes'] ?? []);
        if ($eligibleCodes !== [] && ($discountCode === null || ! in_array(strtolower($discountCode), array_map('strtolower', $eligibleCodes), true))) {
            return [$listPrice, false];
        }

        if (! $this->isUserEligibleForDiscount($rule, $userId)) {
            return [$listPrice, false];
        }

        if ($rule->usage_cap !== null && $rule->usage_count >= $rule->usage_cap) {
            return [$listPrice, false];
        }

        $discountedPrice = match ($rule->discount_type) {
            'percent' => $listPrice - ($listPrice * (float) ($rule->discount_value ?? 0) / 100),
            'fixed' => $listPrice - (float) ($rule->discount_value ?? 0),
            default => $listPrice,
        };

        return [max($discountedPrice, 0), $discountedPrice !== $listPrice];
    }

    private function isDiscountInactive(PlanPriceOverride $rule): bool
    {
        $now = Carbon::now();

        if ($rule->discount_starts_at && $now->lt($rule->discount_starts_at)) {
            return true;
        }

        if ($rule->discount_ends_at && $now->gt($rule->discount_ends_at)) {
            return true;
        }

        return false;
    }

    private function isUserEligibleForDiscount(PlanPriceOverride $rule, ?int $userId): bool
    {
        $eligibleUserIds = collect(Arr::wrap($rule->metadata['eligible_user_ids'] ?? []))
            ->filter()
            ->map(static fn ($id): int => (int) $id)
            ->all();

        if ($eligibleUserIds === []) {
            return true;
        }

        return $userId !== null && in_array($userId, $eligibleUserIds, true);
    }
}
