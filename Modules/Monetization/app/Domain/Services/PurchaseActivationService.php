<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Events\PurchaseActivated;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

class PurchaseActivationService
{
    public function __construct(private readonly PurchaseRepository $purchaseRepository)
    {
    }

    public function activate(AdPlanPurchase $purchase): AdPlanPurchase
    {
        $purchase->loadMissing('plan');

        $startsAt = Carbon::now();
        $endsAt = Carbon::now()->addDays($purchase->plan->duration_days);

        $purchase = $this->purchaseRepository->update($purchase, [
            'payment_status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        Event::dispatch(new PurchaseActivated($purchase));

        return $purchase;
    }
}
