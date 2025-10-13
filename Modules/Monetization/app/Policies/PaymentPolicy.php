<?php

namespace Modules\Monetization\Policies;

use Modules\Monetization\Domain\Entities\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        return $user->getKey() === $payment->user_id || $user->hasRole('admin');
    }

    public function refund(User $user, Payment $payment): bool
    {
        return $user->hasRole('admin');
    }
}
