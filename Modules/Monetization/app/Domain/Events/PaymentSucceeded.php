<?php

namespace Modules\Monetization\Domain\Events;

use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSucceeded
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly ?AdPlanPurchase $purchase = null,
        public readonly ?Payment $payment = null,
    ) {
    }
}
