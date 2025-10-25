<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('notifications', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('notifications', [NotificationController::class, 'store'])->name('notification.store');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->name('notification.show');
    Route::post('notifications/{notification}/update', [NotificationController::class, 'update'])->name('notification.update');
    Route::post('notifications/{notification}/delete', [NotificationController::class, 'destroy'])->name('notification.destroy');
});
