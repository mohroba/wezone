<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\MonetizationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('monetizations', [MonetizationController::class, 'index'])->name('monetization.index');
    Route::post('monetizations', [MonetizationController::class, 'store'])->name('monetization.store');
    Route::get('monetizations/{monetization}', [MonetizationController::class, 'show'])->name('monetization.show');
    Route::post('monetizations/{monetization}/update', [MonetizationController::class, 'update'])->name('monetization.update');
    Route::post('monetizations/{monetization}/delete', [MonetizationController::class, 'destroy'])->name('monetization.destroy');
});
