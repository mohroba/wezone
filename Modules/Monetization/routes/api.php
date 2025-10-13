<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\PaymentController;
use Modules\Monetization\Http\Controllers\PlanController;
use Modules\Monetization\Http\Controllers\PurchaseController;
use Modules\Monetization\Http\Controllers\WalletController;

Route::middleware('auth:api')->prefix('monetization')->group(function (): void {
    Route::get('plans', [PlanController::class, 'index']);

    Route::post('purchases', [PurchaseController::class, 'store']);
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show']);
    Route::post('purchases/{purchase}/bump', [PurchaseController::class, 'bump']);

    Route::post('purchases/{purchase}/payments/initiate', [PaymentController::class, 'initiate']);
    Route::post('payments/verify', [PaymentController::class, 'verify']);

    Route::get('wallet', [WalletController::class, 'show']);
    Route::post('wallet/top-up', [WalletController::class, 'topUp']);
});
