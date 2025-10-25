<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static bool $passportKeysGenerated = false;

    private static bool $cacheServicesRegistered = false;

    private static ?string $appKey = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::$cacheServicesRegistered) {
            config()->set('cache.default', 'array');

            $this->app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
            $this->app->register(\Illuminate\Cache\CacheServiceProvider::class);

            self::$cacheServicesRegistered = true;
        }

        if (self::$appKey === null) {
            self::$appKey = 'base64:'.base64_encode(random_bytes(32));
        }

        config()->set('app.key', self::$appKey);

        if (! self::$passportKeysGenerated) {
            \Illuminate\Support\Facades\Artisan::call('passport:keys', [
                '--no-interaction' => true,
                '--force' => true,
            ]);

            self::$passportKeysGenerated = true;
        }
    }
}
