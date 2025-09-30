<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\Api\AdminSettingController;
use Modules\Settings\Http\Controllers\Api\PublicSettingController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('/', [PublicSettingController::class, 'index']);
        Route::get('{key}', [PublicSettingController::class, 'show']);
    });

    Route::middleware('auth:api')->prefix('admin/settings')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index']);
        Route::post('/', [AdminSettingController::class, 'store']);
    });
});
