<?php

namespace Modules\Monetization\Policies;

use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use App\Models\User;

class PurchasePolicy
{
    public function view(User $user, AdPlanPurchase $purchase): bool
    {
        return $user->getKey() === $purchase->user_id || $user->hasRole('admin');
    }

    public function update(User $user, AdPlanPurchase $purchase): bool
    {
        return $user->hasRole('admin');
    }
}
