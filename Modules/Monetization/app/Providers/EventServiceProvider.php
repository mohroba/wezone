<?php

namespace Modules\Monetization\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Modules\Monetization\Domain\Events\PurchaseActivated;
use Modules\Monetization\Domain\Events\RefundProcessed;
use Modules\Monetization\Domain\Listeners\LogKpiEvent;
use Modules\Monetization\Domain\Listeners\SendUserNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        LogKpiEvent::class,
    ];

    protected $listen = [
        PaymentSucceeded::class => [
            [SendUserNotification::class, 'handlePaymentSucceeded'],
        ],
        PurchaseActivated::class => [
            [SendUserNotification::class, 'handlePurchaseActivated'],
        ],
        RefundProcessed::class => [
            [SendUserNotification::class, 'handleRefundProcessed'],
        ],
    ];
}
