<?php

use Illuminate\Support\Facades\Route;
use Modules\Ad\Http\Controllers\AdAttributeDefinitionController;
use Modules\Ad\Http\Controllers\AdAttributeGroupController;
use Modules\Ad\Http\Controllers\AdAttributeValueController;
use Modules\Ad\Http\Controllers\AdCategoryController;
use Modules\Ad\Http\Controllers\AdController;

Route::middleware(['api'])->group(function (): void {
    Route::get('ads', [AdController::class, 'index']);
    Route::post('ads', [AdController::class, 'store']);
    Route::get('ads/{ad}', [AdController::class, 'show']);
    Route::post('ads/{ad}/update', [AdController::class, 'update']);
    Route::post('ads/{ad}/delete', [AdController::class, 'destroy']);

    Route::get('ad-categories', [AdCategoryController::class, 'index']);
    Route::post('ad-categories', [AdCategoryController::class, 'store']);
    Route::get('ad-categories/{ad_category}', [AdCategoryController::class, 'show']);
    Route::post('ad-categories/{ad_category}/update', [AdCategoryController::class, 'update']);
    Route::post('ad-categories/{ad_category}/delete', [AdCategoryController::class, 'destroy']);

    Route::get('ad-attribute-groups', [AdAttributeGroupController::class, 'index']);
    Route::post('ad-attribute-groups', [AdAttributeGroupController::class, 'store']);
    Route::get('ad-attribute-groups/{ad_attribute_group}', [AdAttributeGroupController::class, 'show']);
    Route::post('ad-attribute-groups/{ad_attribute_group}/update', [AdAttributeGroupController::class, 'update']);
    Route::post('ad-attribute-groups/{ad_attribute_group}/delete', [AdAttributeGroupController::class, 'destroy']);

    Route::get('ad-attribute-definitions', [AdAttributeDefinitionController::class, 'index']);
    Route::post('ad-attribute-definitions', [AdAttributeDefinitionController::class, 'store']);
    Route::get('ad-attribute-definitions/{ad_attribute_definition}', [AdAttributeDefinitionController::class, 'show']);
    Route::post('ad-attribute-definitions/{ad_attribute_definition}/update', [AdAttributeDefinitionController::class, 'update']);
    Route::post('ad-attribute-definitions/{ad_attribute_definition}/delete', [AdAttributeDefinitionController::class, 'destroy']);

    Route::get('ad-attribute-values', [AdAttributeValueController::class, 'index']);
    Route::post('ad-attribute-values', [AdAttributeValueController::class, 'store']);
    Route::get('ad-attribute-values/{ad_attribute_value}', [AdAttributeValueController::class, 'show']);
    Route::post('ad-attribute-values/{ad_attribute_value}/update', [AdAttributeValueController::class, 'update']);
    Route::post('ad-attribute-values/{ad_attribute_value}/delete', [AdAttributeValueController::class, 'destroy']);
});
