<?php

use Illuminate\Support\Facades\Route;
use Modules\Ad\Http\Controllers\AdAttributeDefinitionController;
use Modules\Ad\Http\Controllers\AdAttributeGroupController;
use Modules\Ad\Http\Controllers\AdAttributeValueController;
use Modules\Ad\Http\Controllers\AdCategoryController;
use Modules\Ad\Http\Controllers\AdCommentController;
use Modules\Ad\Http\Controllers\AdController;
use Modules\Ad\Http\Controllers\AdReportController;
use Modules\Ad\Http\Controllers\AdvertisableTypeController;

Route::middleware(['api'])->group(function (): void {
    Route::get('ads', [AdController::class, 'index']);
    Route::post('ads', [AdController::class, 'store']);
    Route::get('ads/{ad}', [AdController::class, 'show']);
    Route::post('ads/{ad}/update', [AdController::class, 'update']);
    Route::post('ads/{ad}/delete', [AdController::class, 'destroy']);
    Route::post('ads/{ad}/images', [AdController::class, 'storeImages']);
    Route::get('ads/{ad}/comments', [AdCommentController::class, 'index']);
    Route::post('ads/{ad}/comments', [AdCommentController::class, 'store'])->middleware('auth:api');

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

    Route::post('ad-reports', [AdReportController::class, 'store'])->middleware('auth:api');

    Route::get('advertisable-types', [AdvertisableTypeController::class, 'index']);
    Route::get('advertisable-types/{key}', [AdvertisableTypeController::class, 'show']);
});

Route::middleware(['api', 'auth:api'])->group(function (): void {
    Route::apiResource('ad-reports', AdReportController::class)->except(['store']);
    Route::post('ad-reports/{ad_report}/resolve', [AdReportController::class, 'resolve'])->name('ad-reports.resolve');
    Route::post('ad-reports/{ad_report}/dismiss', [AdReportController::class, 'dismiss'])->name('ad-reports.dismiss');
});
