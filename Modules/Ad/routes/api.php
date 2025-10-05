<?php

use Illuminate\Support\Facades\Route;

Route::prefix('ads')->middleware(['api'])->group(function (): void {
    // API routes for the Ads module will be registered here.
});
