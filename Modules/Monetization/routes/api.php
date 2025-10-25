<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\PaymentController;
use Modules\Monetization\Http\Controllers\PlanController;
use Modules\Monetization\Http\Controllers\PurchaseController;
use Modules\Monetization\Http\Controllers\WalletController;

Route::middleware(['auth:api'])->prefix('monetization')->group(function (): void {
    Route::get('plans', [PlanController::class, 'index'])->name('monetization.plans.index');

    Route::post('purchases', [PurchaseController::class, 'store'])->name('monetization.purchases.store');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('monetization.purchases.show');
    Route::post('purchases/{purchase}/bump', [PurchaseController::class, 'bump'])->name('monetization.purchases.bump');

    Route::post('purchases/{purchase}/payments/initiate', [PaymentController::class, 'initiate'])->name('monetization.payments.initiate');
    Route::post('payments/verify', [PaymentController::class, 'verify'])->name('monetization.payments.verify');

    Route::get('wallet', [WalletController::class, 'show'])->name('monetization.wallet.show');
    Route::post('wallet/top-up', [WalletController::class, 'topUp'])->name('monetization.wallet.top-up');
});
