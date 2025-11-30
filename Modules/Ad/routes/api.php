<?php

use Illuminate\Support\Facades\Route;
use Modules\Ad\Http\Controllers\AdAttributeDefinitionController;
use Modules\Ad\Http\Controllers\AdAttributeGroupController;
use Modules\Ad\Http\Controllers\AdAttributeValueController;
use Modules\Ad\Http\Controllers\AdCategoryController;
use Modules\Ad\Http\Controllers\AdCommentController;
use Modules\Ad\Http\Controllers\AdController;
use Modules\Ad\Http\Controllers\AdExploreController;
use Modules\Ad\Http\Controllers\AdImageController;
use Modules\Ad\Http\Controllers\AdConversationController;
use Modules\Ad\Http\Controllers\AdMessageController;
use Modules\Ad\Http\Controllers\AdFavoriteController;
use Modules\Ad\Http\Controllers\AdLikeController;
use Modules\Ad\Http\Controllers\AdReportController;
use Modules\Ad\Http\Controllers\AdvertisableTypeController;

Route::middleware(['api'])->group(function (): void {

    Route::middleware('auth:api')->group(function (): void {
        // --- Conversations & Messages ---
        Route::get('ads/conversations', [AdConversationController::class, 'index']);
        Route::post('ads/{ad}/conversations', [AdConversationController::class, 'store']);
        Route::post('ads/conversations/{conversation}/delete', [AdConversationController::class, 'destroy']);

        Route::get('ads/conversations/{conversation}/messages', [AdMessageController::class, 'index']);
        Route::post('ads/conversations/{conversation}/messages', [AdMessageController::class, 'store']);

        // --- Bookmarks & Likes ---
        Route::get('ads/bookmarks', [AdFavoriteController::class, 'index']);
    });

    // --- Ads CRUD ---
    Route::get('ads/explore', [AdExploreController::class, 'index']);
    Route::get('ads', [AdController::class, 'index']);
    Route::post('ads', [AdController::class, 'store']);
    Route::get('ads/{ad}', [AdController::class, 'show']);
    Route::post('ads/{ad}/update', [AdController::class, 'update']);
    Route::post('ads/{ad}/delete', [AdController::class, 'destroy']);
    Route::post('ads/{ad}/seen', [AdController::class, 'markSeen']);

    // --- Ad Comments ---
    Route::get('ads/{ad}/comments', [AdCommentController::class, 'index']);
    Route::get('ads/{ad}/comments/threaded', [AdCommentController::class, 'threaded']);
    Route::post('ads/{ad}/comments', [AdCommentController::class, 'store'])->middleware('auth:api');

    // --- Ad Categories ---
    Route::get('ad-categories', [AdCategoryController::class, 'index']);
    Route::post('ad-categories', [AdCategoryController::class, 'store']);
    Route::get('ad-categories/{ad_category}', [AdCategoryController::class, 'show']);
    Route::post('ad-categories/{ad_category}/update', [AdCategoryController::class, 'update']);
    Route::post('ad-categories/{ad_category}/delete', [AdCategoryController::class, 'destroy']);

    // --- Attribute Groups ---
    Route::get('ad-attribute-groups', [AdAttributeGroupController::class, 'index']);
    Route::post('ad-attribute-groups', [AdAttributeGroupController::class, 'store']);
    Route::get('ad-attribute-groups/{ad_attribute_group}', [AdAttributeGroupController::class, 'show']);
    Route::post('ad-attribute-groups/{ad_attribute_group}/update', [AdAttributeGroupController::class, 'update']);
    Route::post('ad-attribute-groups/{ad_attribute_group}/delete', [AdAttributeGroupController::class, 'destroy']);

    // --- Attribute Definitions ---
    Route::get('ad-attribute-definitions', [AdAttributeDefinitionController::class, 'index']);
    Route::post('ad-attribute-definitions', [AdAttributeDefinitionController::class, 'store']);
    Route::get('ad-attribute-definitions/{ad_attribute_definition}', [AdAttributeDefinitionController::class, 'show']);
    Route::post('ad-attribute-definitions/{ad_attribute_definition}/update', [AdAttributeDefinitionController::class, 'update']);
    Route::post('ad-attribute-definitions/{ad_attribute_definition}/delete', [AdAttributeDefinitionController::class, 'destroy']);

    // --- Attribute Values ---
    Route::get('ad-attribute-values', [AdAttributeValueController::class, 'index']);
    Route::post('ad-attribute-values', [AdAttributeValueController::class, 'store']);
    Route::get('ad-attribute-values/{ad_attribute_value}', [AdAttributeValueController::class, 'show']);
    Route::post('ad-attribute-values/{ad_attribute_value}/update', [AdAttributeValueController::class, 'update']);
    Route::post('ad-attribute-values/{ad_attribute_value}/delete', [AdAttributeValueController::class, 'destroy']);

    // --- Reports (public submission) ---
    Route::post('ad-reports', [AdReportController::class, 'store'])->middleware('auth:api');

    // --- Advertisable Types ---
    Route::get('advertisable-types/classes', [AdvertisableTypeController::class, 'classes']);
    Route::get('advertisable-types', [AdvertisableTypeController::class, 'index']);
    Route::post('advertisable-types', [AdvertisableTypeController::class, 'store']);
    Route::post('advertisable-types/{advertisable_type}/update', [AdvertisableTypeController::class, 'update']);
    Route::post('advertisable-types/{advertisable_type}/delete', [AdvertisableTypeController::class, 'destroy']);
    Route::get('advertisable-types/{key}', [AdvertisableTypeController::class, 'show']);
});

Route::middleware(['api', 'auth:api'])->group(function (): void {
    // --- Image management ---
    Route::get('ads/{ad}/images', [AdImageController::class, 'index']);
    Route::post('ads/{ad}/images', [AdImageController::class, 'store']);
    Route::post('ads/{ad}/images/{media}/update', [AdImageController::class, 'update']);
    Route::delete('ads/{ad}/images/{media}', [AdImageController::class, 'destroy']);
    Route::post('ads/{ad}/images/reorder', [AdImageController::class, 'reorder']);

    // --- Admin or Authenticated Report Management ---
    Route::get('ad-reports', [AdReportController::class, 'index'])->name('ad-reports.index');
    Route::get('ad-reports/{ad_report}', [AdReportController::class, 'show'])->name('ad-reports.show');
    Route::post('ad-reports/{ad_report}/update', [AdReportController::class, 'update'])->name('ad-reports.update');
    Route::post('ad-reports/{ad_report}/delete', [AdReportController::class, 'destroy'])->name('ad-reports.destroy');
    Route::post('ad-reports/{ad_report}/resolve', [AdReportController::class, 'resolve'])->name('ad-reports.resolve');
    Route::post('ad-reports/{ad_report}/dismiss', [AdReportController::class, 'dismiss'])->name('ad-reports.dismiss');

    // --- User Actions ---
    Route::post('ads/{ad}/bookmark', [AdFavoriteController::class, 'toggle']);
    Route::post('ads/{ad}/like', [AdLikeController::class, 'toggle']);
});
