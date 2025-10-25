<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\Api\NotificationController;

Route::middleware(['auth:api'])->prefix('v1')->group(function (): void {
    Route::get('notifications', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('notifications/read', [NotificationController::class, 'markAllAsRead'])->name('notification.mark-all-read');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notification.mark-read');
    Route::post('notifications/{notification}/acknowledge', [NotificationController::class, 'acknowledge'])->name('notification.acknowledge');
});
