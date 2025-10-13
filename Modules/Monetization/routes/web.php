<?php

use Illuminate\Support\Facades\Route;
use Modules\Monetization\Http\Controllers\MonetizationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('monetizations', MonetizationController::class)->names('monetization');
});
