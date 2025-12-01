<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\PaymentController;
use Modules\Monetization\Http\Controllers\DiscountCodeController;
use Modules\Monetization\Http\Controllers\PlanController;
use Modules\Monetization\Http\Controllers\PurchaseController;
use Modules\Monetization\Http\Controllers\WalletController;
use Modules\Monetization\Http\Controllers\Admin\PlanPriceRuleController;
use Modules\Monetization\Http\Controllers\Admin\DiscountCodeAdminController;
use Modules\Monetization\Http\Controllers\Admin\DiscountRedemptionReportController;

Route::middleware(['auth:api'])->prefix('monetization')->group(function (): void {
    Route::get('plans', [PlanController::class, 'index'])->name('monetization.plans.index');

    Route::post('purchases', [PurchaseController::class, 'store'])->name('monetization.purchases.store');
    Route::post('purchases/bulk', [PurchaseController::class, 'storeMany'])->name('monetization.purchases.storeMany');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('monetization.purchases.show');
    Route::post('purchases/{purchase}/bump', [PurchaseController::class, 'bump'])->name('monetization.purchases.bump');

    Route::get('payments', [PaymentController::class, 'index'])->name('monetization.payments.index');
    Route::post('payments', [PaymentController::class, 'store'])->name('monetization.payments.store');
    Route::post('purchases/{purchase}/payments/initiate', [PaymentController::class, 'initiate'])->name('monetization.payments.initiate');
    Route::post('payments/{payment}/validate', [PaymentController::class, 'validatePayment'])->name('monetization.payments.validate');
    Route::post('payments/verify', [PaymentController::class, 'verify'])->name('monetization.payments.verify');
    Route::get('ads/{ad}/payments', [PaymentController::class, 'adPayments'])->name('monetization.ads.payments');

    Route::post('discount-codes/validate', [DiscountCodeController::class, 'validateCode'])
        ->name('monetization.discount-codes.validate');

    Route::get('wallet', [WalletController::class, 'show'])->name('monetization.wallet.show');
    Route::post('wallet/top-up', [WalletController::class, 'topUp'])->name('monetization.wallet.top-up');
});

Route::middleware(['auth:api'])->prefix('monetization/admin')->group(function (): void {
    Route::get('plans/{plan}/price-rules', [PlanPriceRuleController::class, 'index']);
    Route::post('plans/{plan}/price-rules', [PlanPriceRuleController::class, 'store']);
    Route::get('price-rules/{priceRule}', [PlanPriceRuleController::class, 'show']);
    Route::put('price-rules/{priceRule}', [PlanPriceRuleController::class, 'update']);
    Route::delete('price-rules/{priceRule}', [PlanPriceRuleController::class, 'destroy']);

    Route::get('price-rules/{priceRule}/discount-codes', [DiscountCodeAdminController::class, 'index']);
    Route::post('price-rules/{priceRule}/discount-codes', [DiscountCodeAdminController::class, 'store']);
    Route::get('discount-codes/{discountCode}', [DiscountCodeAdminController::class, 'show']);
    Route::put('discount-codes/{discountCode}', [DiscountCodeAdminController::class, 'update']);
    Route::delete('discount-codes/{discountCode}', [DiscountCodeAdminController::class, 'destroy']);

    Route::get('discount-redemptions', [DiscountRedemptionReportController::class, 'index']);
});
