<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['api','auth:api'])->prefix('v1')->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::get('settings/{setting}', [SettingsController::class, 'show'])->name('settings.show');
    Route::post('settings/{setting}/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/{setting}/delete', [SettingsController::class, 'destroy'])->name('settings.destroy');
});
