<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\GeolocationController;
use App\Http\Controllers\Api\ProvinceController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::prefix('geography')->middleware("auth:api")->group(function () {
    Route::get('countries', [CountryController::class, 'index']);
    Route::get('countries/{country}', [CountryController::class, 'show']);

    Route::get('provinces', [ProvinceController::class, 'index']);
    Route::get('provinces/{province}', [ProvinceController::class, 'show']);
    Route::get('provinces/{province}/cities', [ProvinceController::class, 'cities']);

    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{city}', [CityController::class, 'show']);

    Route::get('locations/lookup', [GeolocationController::class, 'lookup']);
    Route::get('locations/user-city', [GeolocationController::class, 'resolveUserCity']);
    Route::get('locations/nearby-cities', [GeolocationController::class, 'nearbyCities']);
});


