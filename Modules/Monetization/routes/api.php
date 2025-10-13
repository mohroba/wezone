<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\MonetizationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('monetizations', MonetizationController::class)->names('monetization');
});
