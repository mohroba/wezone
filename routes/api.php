<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\Kpi\DeviceMetricsController;
use App\Http\Controllers\Api\Kpi\EventController;
use App\Http\Controllers\Api\Kpi\InstallationController;
use App\Http\Controllers\Api\Kpi\SessionController;
use App\Http\Controllers\Api\Kpi\UninstallationController;
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

Route::prefix('kpi')->group(function () {
    Route::post('devices/register', [DeviceMetricsController::class, 'register']);
    Route::post('devices/heartbeat', [DeviceMetricsController::class, 'heartbeat']);
    Route::post('installations', [InstallationController::class, 'store']);
    Route::post('uninstallations', [UninstallationController::class, 'store']);
    Route::post('sessions', [SessionController::class, 'store']);
    Route::patch('sessions/{session:session_uuid}', [SessionController::class, 'update']);
    Route::post('events', [EventController::class, 'store']);
});


