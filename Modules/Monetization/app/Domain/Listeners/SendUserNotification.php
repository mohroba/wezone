<?php

namespace Modules\Monetization\Domain\Listeners;

use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Modules\Monetization\Domain\Events\PurchaseActivated;
use Modules\Monetization\Domain\Events\RefundProcessed;
use Illuminate\Support\Facades\Log;

class SendUserNotification
{
    public function handlePaymentSucceeded(PaymentSucceeded $event): void
    {
        Log::info('monetization.notifications.payment_succeeded', [
            'user_id' => $event->payment?->user_id,
            'payment_id' => $event->payment?->getKey(),
        ]);
    }

    public function handlePurchaseActivated(PurchaseActivated $event): void
    {
        Log::info('monetization.notifications.purchase_activated', [
            'purchase_id' => $event->purchase?->getKey(),
        ]);
    }

    public function handleRefundProcessed(RefundProcessed $event): void
    {
        Log::info('monetization.notifications.refund_processed', [
            'payment_id' => $event->payment?->getKey(),
        ]);
    }
}
