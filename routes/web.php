<?php

use App\Support\ApiResponse;
use Illuminate\Support\Facades\Route;
use Metti\LaravelSms\Facade\SendSMS;

Route::get('/', function () {
    return ApiResponse::success('API is running.');
});

// routes/web.php (protect this!)
Route::post('/deploy/run', function () {
    abort_unless(request()->header('X-Deploy-Token') === config('app.deploy_token'), 403);

    \Artisan::call('optimize:clear');
    \Artisan::call('config:cache');
    \Artisan::call('route:cache');
    \Artisan::call('view:cache');
    \Artisan::call('queue:restart');
    \Artisan::call('migrate', ['--force' => true]);
    \Artisan::call('scribe:generate', ['--force' => true]);

    return response()->json(['ok' => true]);
});


Route::get('/test-sms', function () {
    SendSMS::via('ippanel')
        ->patternMessage('verify',['code' => '12345'])
        ->recipients(['09212663231'])
        ->send();
});

