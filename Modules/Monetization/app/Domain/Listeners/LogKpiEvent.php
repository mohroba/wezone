<?php

namespace Modules\Monetization\Domain\Listeners;

use Modules\Monetization\Domain\Events\PaymentFailed;
use Modules\Monetization\Domain\Events\PaymentInitiated;
use Modules\Monetization\Domain\Events\PaymentSucceeded;
use Modules\Monetization\Domain\Events\PlanSelected;
use Modules\Monetization\Domain\Events\PurchaseActivated;
use Modules\Monetization\Domain\Events\PurchaseExpired;
use Modules\Monetization\Domain\Events\RefundProcessed;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class LogKpiEvent
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(PlanSelected::class, [$this, 'handlePlanSelected']);
        $events->listen(PaymentInitiated::class, [$this, 'handlePaymentInitiated']);
        $events->listen(PaymentSucceeded::class, [$this, 'handlePaymentSucceeded']);
        $events->listen(PaymentFailed::class, [$this, 'handlePaymentFailed']);
        $events->listen(PurchaseActivated::class, [$this, 'handlePurchaseActivated']);
        $events->listen(PurchaseExpired::class, [$this, 'handlePurchaseExpired']);
        $events->listen(RefundProcessed::class, [$this, 'handleRefundProcessed']);
    }

    public function handlePlanSelected(PlanSelected $event): void
    {
        $this->log('plan_selected', $event->purchase?->id, $event->purchase?->correlation_id);
    }

    public function handlePaymentInitiated(PaymentInitiated $event): void
    {
        $this->log('payment_initiated', $event->payment?->id, $event->payment?->correlation_id);
    }

    public function handlePaymentSucceeded(PaymentSucceeded $event): void
    {
        $this->log('payment_succeeded', $event->payment?->id, $event->payment?->correlation_id);
    }

    public function handlePaymentFailed(PaymentFailed $event): void
    {
        $this->log('payment_failed', $event->payment?->id, $event->payment?->correlation_id);
    }

    public function handlePurchaseActivated(PurchaseActivated $event): void
    {
        $this->log('purchase_activated', $event->purchase?->id, $event->purchase?->correlation_id);
    }

    public function handlePurchaseExpired(PurchaseExpired $event): void
    {
        $this->log('purchase_expired', $event->purchase?->id, $event->purchase?->correlation_id);
    }

    public function handleRefundProcessed(RefundProcessed $event): void
    {
        $this->log('refund_processed', $event->payment?->id, $event->payment?->correlation_id);
    }

    private function log(string $action, ?int $subjectId, ?string $correlationId): void
    {
        Log::info('monetization.kpi', [
            'action' => $action,
            'subject_id' => $subjectId,
            'correlation_id' => $correlationId,
        ]);
    }
}
