<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\FollowController;
use Modules\User\Http\Controllers\UserIndexController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:api')->prefix('users')->group(function () {
        Route::post('{user}/follow', [FollowController::class, 'store']);
        Route::post('{user}/unfollow', [FollowController::class, 'destroy']);
        Route::get('{user}/followers', [FollowController::class, 'index']);

        Route::get('/', [UserIndexController::class, 'index']);
    });
});
