<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\MonetizationController;
use Modules\Monetization\Http\Controllers\WalletController;

Route::middleware(['auth:api'])->group(function (): void {
    Route::apiResource('monetizations', MonetizationController::class)->names('monetization');

    Route::get('wallet', [WalletController::class, 'show']);
    Route::post('wallet/top-up', [WalletController::class, 'topUp']);
});
