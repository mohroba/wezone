<?php

namespace Modules\Monetization\Policies;

use Modules\Monetization\Domain\Entities\Plan;
use App\Models\User;

class PlanPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function manage(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Plan $plan): bool
    {
        return $this->manage($user);
    }
}
