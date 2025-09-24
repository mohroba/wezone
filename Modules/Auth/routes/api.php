<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\MobileAuthController;
use Modules\Auth\Http\Controllers\Api\ProfileController;
use Modules\Auth\Http\Controllers\Api\UserController;

Route::prefix('auth')->group(function () {
    Route::post('otp/send', [MobileAuthController::class, 'send']);
    Route::post('otp/verify', [MobileAuthController::class, 'verify']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [ProfileController::class, 'show']);
        Route::post('profile', [ProfileController::class, 'update']);

        Route::get('user', [UserController::class, 'show']);
        Route::post('user', [UserController::class, 'update']);
    });
});
