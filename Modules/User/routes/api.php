<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\BlockController;
use Modules\User\Http\Controllers\FollowController;
use Modules\User\Http\Controllers\UserIndexController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:api')->prefix('users')->group(function () {
        Route::post('{user}/follow', [FollowController::class, 'store']);
        Route::delete('{user}/follow', [FollowController::class, 'destroy']);
        Route::get('{user}/followers', [FollowController::class, 'index']);
        Route::post('{user}/block', [BlockController::class, 'store']);
        Route::post('{user}/unblock', [BlockController::class, 'unblock']);
        Route::get('blocks', [BlockController::class, 'index']);

        Route::get('/', [UserIndexController::class, 'index']);
    });
});
