<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Events\PurchaseExpired;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

class ExpirePurchases
{
    public function __invoke(): void
    {
        AdPlanPurchase::query()
            ->where('payment_status', 'active')
            ->where('ends_at', '<', Carbon::now())
            ->each(function (AdPlanPurchase $purchase): void {
                $purchase->update(['payment_status' => 'expired']);
                Event::dispatch(new PurchaseExpired($purchase));
            });
    }
}
