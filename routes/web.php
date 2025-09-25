<?php

use App\Support\ApiResponse;
use Illuminate\Support\Facades\Route;
use Metti\LaravelSms\Facade\SendSMS;

Route::get('/', function () {
    return ApiResponse::success('API is running.');
});

Route::get('/test-sms', function () {
    SendSMS::via('ippanel')
        ->patternMessage('verify',['code' => '12345'])
        ->recipients(['09212663231'])
        ->send();
});

