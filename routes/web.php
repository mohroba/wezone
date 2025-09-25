<?php

use App\Support\ApiResponse;
use Illuminate\Support\Facades\Route;
use Metti\LaravelSms\Facade\SendSMS;

Route::get('/', function () {
    return ApiResponse::success('API is running.');
});

Route::any('/deploy/unpacking', function () {
    \Artisan::call('optimize:clear');
    \Artisan::call('queue:restart');

    $laravelRoot = base_path();                              // /home/wezapp/wezone_laravel
    $docroot     = realpath($laravelRoot . '/../public_html/api'); // /home/wezapp/public_html/api
    $zipFile     = $laravelRoot . '/app.zip';

    if (!file_exists($zipFile)) {
        return response()->json(['ok' => false, 'error' => 'app.zip not found'], 404);
    }

    $zip = new ZipArchive;
    if ($zip->open($zipFile) !== true) {
        return response()->json(['ok' => false, 'error' => 'cannot open zip'], 500);
    }
    $zip->extractTo($laravelRoot);   // overwrites matched files; non-matching old files remain
    $zip->close();
    @unlink($zipFile);               // cleanup
    // ZipArchive::extractTo overwrites existing names; unmatched files stay (hence we clean target asset dirs below). :contentReference[oaicite:2]{index=2}

    // helpers
    $rrmdir = function ($dir) use (&$rrmdir) {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path) && !is_link($path)) $rrmdir($path);
            else @unlink($path);
        }
        @rmdir($dir);
    };
    $rcopy = function ($src, $dst) use (&$rcopy) {
        if (!is_dir($src)) return;
        if (!is_dir($dst)) mkdir($dst, 0755, true);
        $dh = opendir($src);
        while (($file = readdir($dh)) !== false) {
            if ($file === '.' || $file === '..') continue;
            $srcPath = $src . DIRECTORY_SEPARATOR . $file;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $file;
            if (is_dir($srcPath)) $rcopy($srcPath, $dstPath);
            else copy($srcPath, $dstPath);
        }
        closedir($dh);
    };

    // clean-slate copy for hashed assets and docs
    $srcBuild = $laravelRoot . '/public/build';
    $dstBuild = $docroot . '/build';
    $rrmdir($dstBuild);
    if (is_dir($srcBuild)) $rcopy($srcBuild, $dstBuild);

    $srcDocs = $laravelRoot . '/public/docs';
    $dstDocs = $docroot . '/docs';
    $rrmdir($dstDocs);
    if (is_dir($srcDocs)) $rcopy($srcDocs, $dstDocs);

    // rebuild caches (optional)
    try {
        \Artisan::call('optimize:clear');
        \Artisan::call('config:cache');
        \Artisan::call('route:cache');
        \Artisan::call('view:cache');
        \Artisan::call('queue:restart');
        \Artisan::call('migrate', ['--force' => true]);
    } catch (\Throwable $e) {}

    return response()->json(['ok' => true]);
});



Route::get('/test-sms', function () {
    SendSMS::via('ippanel')
        ->patternMessage('verify',['code' => '12345'])
        ->recipients(['09212663231'])
        ->send();
});

