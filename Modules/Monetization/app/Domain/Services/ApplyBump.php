<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Arr;

class ApplyBump
{
    public function __construct(private readonly PurchaseRepository $purchaseRepository)
    {
    }

    public function __invoke(AdPlanPurchase $purchase): AdPlanPurchase
    {
        if ($purchase->payment_status !== 'active') {
            throw new InvalidArgumentException('Purchase is not active.');
        }

        if ($purchase->bump_allowance <= 0) {
            throw new InvalidArgumentException('No bump allowance remaining.');
        }

        $cooldown = $this->resolveCooldownMinutes($purchase);
        $lastBumpedAt = $purchase->meta['last_bumped_at'] ?? null;
        if ($lastBumpedAt && Carbon::parse($lastBumpedAt)->diffInMinutes(now()) < $cooldown) {
            throw new InvalidArgumentException("Bump cooldown of {$cooldown} minutes has not elapsed.");
        }

        $meta = $purchase->meta;
        $meta['last_bumped_at'] = now()->toIso8601String();

        return $this->purchaseRepository->update($purchase, [
            'bump_allowance' => $purchase->bump_allowance - 1,
            'meta' => $meta,
        ]);
    }

    private function resolveCooldownMinutes(AdPlanPurchase $purchase): int
    {
        $plan = $purchase->relationLoaded('plan') ? $purchase->plan : $purchase->plan()->first();

        return (int) (
            $purchase->bump_cooldown_minutes
            ?? $plan?->bump_cooldown_minutes
            ?? Arr::get($plan?->features, 'bump.cooldown_minutes')
            ?? config('monetization.features.bump.cooldown_minutes')
        );
    }
}
