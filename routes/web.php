<?php

use Illuminate\Support\Facades\Route;
use Metti\LaravelSms\Facade\SendSMS;

Route::get('/debug-vite', function () {
    \Artisan::call('optimize:clear');
    \Artisan::call('queue:restart');
    \Artisan::call('storage:link');
    \Artisan::call('module:migrate');
    \Artisan::call('migrate');
    \Artisan::call('db:seed');
    $path = public_path('build/manifest.json');
    return response()->json([
        'public_path' => $path,
        'public_path' => public_path(),
        'srorage_path' => storage_path(),
        'exists' => file_exists($path),
        'size' => file_exists($path) ? filesize($path) : 0,
    ]);
});

Route::get('/test-sms', function () {
    SendSMS::via('ippanel')
        ->patternMessage('verify',['code' => '1234'])
        ->recipients(['09196373450'])
        ->send();
});

