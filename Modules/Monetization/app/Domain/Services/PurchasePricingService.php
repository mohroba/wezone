<?php

namespace Modules\Monetization\Domain\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Modules\Monetization\Domain\DTO\PurchasePrice;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Domain\Entities\DiscountCode;

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
            $plan->load('priceOverrides.discountCodes');
        } else {
            $plan->loadMissing('priceOverrides.discountCodes');
        }

        $priceRule = $this->matchPriceRule($plan, $advertisableTypeId, $adCategoryId);
        $listPrice = $priceRule?->override_price ?? $plan->price;
        $currency = $priceRule?->currency ?? $plan->currency;

        [$discountedPrice, $discountApplied, $resolvedCode] = $this->applyRuleDiscount(
            $priceRule,
            $listPrice,
            $discountCode,
            $userId,
        );

        $appliedDiscountCode = $discountApplied ? $resolvedCode?->code : null;

        return new PurchasePrice(
            listPrice: $listPrice,
            discountedPrice: $discountedPrice,
            currency: $currency,
            priceRule: $priceRule,
            discountCode: $appliedDiscountCode,
            discountCodeEntity: $resolvedCode,
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
     * @return array{0: float, 1: bool, 2: DiscountCode|null}
     */
    private function applyRuleDiscount(?PlanPriceOverride $rule, float $listPrice, ?string $discountCode, ?int $userId): array
    {
        if (! $rule || $rule->discount_type === 'none') {
            return [$listPrice, false, null];
        }

        if ($this->isDiscountInactive($rule)) {
            return [$listPrice, false, null];
        }

        $eligibleCodes = Arr::wrap($rule->metadata['discount_codes'] ?? []);

        if ($eligibleCodes === [] && $rule->discountCodes->isNotEmpty()) {
            $eligibleCodes = $rule->discountCodes->pluck('code')->all();
        }
        $resolvedCode = $this->findMatchingDiscountCode($rule, $discountCode, $userId);

        if ($eligibleCodes !== [] && ($resolvedCode === null || $discountCode === null)) {
            return [$listPrice, false, null];
        }

        if (! $this->isUserEligibleForDiscount($rule, $userId)) {
            return [$listPrice, false, null];
        }

        if ($rule->usage_cap !== null && $rule->usage_count >= $rule->usage_cap) {
            return [$listPrice, false, null];
        }

        $autoDiscountWithoutCodes = $eligibleCodes === [];

        if ($discountCode !== null && $resolvedCode === null) {
            return [$listPrice, false, null];
        }

        if ($resolvedCode && (! $rule->is_stackable || ! $resolvedCode->is_stackable) && $autoDiscountWithoutCodes) {
            return [$listPrice, false, null];
        }

        $discountedPrice = match ($rule->discount_type) {
            'percent' => $listPrice - ($listPrice * (float) ($rule->discount_value ?? 0) / 100),
            'fixed' => $listPrice - (float) ($rule->discount_value ?? 0),
            default => $listPrice,
        };

        return [max($discountedPrice, 0), $discountedPrice !== $listPrice, $resolvedCode];
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

    private function findMatchingDiscountCode(PlanPriceOverride $rule, ?string $discountCode, ?int $userId): ?DiscountCode
    {
        if ($discountCode === null) {
            return null;
        }

        $normalized = strtolower($discountCode);

        $candidate = $rule->discountCodes
            ->first(fn (DiscountCode $code) => strtolower($code->code) === $normalized);

        if (! $candidate) {
            return null;
        }

        $now = Carbon::now();

        if ($candidate->starts_at && $now->lt($candidate->starts_at)) {
            return null;
        }

        if ($candidate->ends_at && $now->gt($candidate->ends_at)) {
            return null;
        }

        if ($candidate->usage_cap !== null && $candidate->usage_count >= $candidate->usage_cap) {
            return null;
        }

        if ($candidate->per_user_cap !== null && $userId !== null) {
            $userRedemptions = $candidate->redemptions()->where('user_id', $userId)->count();

            if ($userRedemptions >= $candidate->per_user_cap) {
                return null;
            }
        }

        return $candidate;
    }
}
