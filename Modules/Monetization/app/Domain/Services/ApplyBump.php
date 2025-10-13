<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

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

        $cooldown = config('monetization.features.bump.cooldown_minutes');
        $lastBumpedAt = $purchase->meta['last_bumped_at'] ?? null;
        if ($lastBumpedAt && Carbon::parse($lastBumpedAt)->diffInMinutes(now()) < $cooldown) {
            throw new InvalidArgumentException('Bump cooldown has not elapsed.');
        }

        $meta = $purchase->meta;
        $meta['last_bumped_at'] = now()->toIso8601String();

        return $this->purchaseRepository->update($purchase, [
            'bump_allowance' => $purchase->bump_allowance - 1,
            'meta' => $meta,
        ]);
    }
}
