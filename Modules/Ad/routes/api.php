<?php

use Illuminate\Support\Facades\Route;
use Modules\Ad\Http\Controllers\AdAttributeDefinitionController;
use Modules\Ad\Http\Controllers\AdAttributeGroupController;
use Modules\Ad\Http\Controllers\AdAttributeValueController;
use Modules\Ad\Http\Controllers\AdCategoryController;
use Modules\Ad\Http\Controllers\AdController;

Route::middleware(['api'])->group(function (): void {
    Route::apiResource('ads', AdController::class);
    Route::apiResource('ad-categories', AdCategoryController::class);
    Route::apiResource('ad-attribute-groups', AdAttributeGroupController::class);
    Route::apiResource('ad-attribute-definitions', AdAttributeDefinitionController::class);
    Route::apiResource('ad-attribute-values', AdAttributeValueController::class);
});
