<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['auth:api'])->group(function (): void {
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
});
