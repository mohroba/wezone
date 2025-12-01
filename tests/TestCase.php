<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;
use Laravel\Passport\Passport;

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

    protected function beforeRefreshingDatabase()
    {
        Passport::ignoreMigrations();
        $duplicateOauthMigrations = collect(File::files(database_path('migrations')))
            ->filter(fn ($file) => str_contains($file->getFilename(), 'create_oauth_') && ! str_contains($file->getFilename(), '2025_09_25_'))
            ->map(fn ($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->all();

        $mysqlSpecificMigrations = [
            '2025_11_15_224903_refactor_advertisable_architecture',
        ];

        \Illuminate\Database\Migrations\Migrator::withoutMigrations(array_merge(
            $duplicateOauthMigrations,
            $mysqlSpecificMigrations,
        ));
    }

    protected function migrateFreshUsing()
    {
        $baseMigrations = collect(File::files(database_path('migrations')))
            ->reject(fn ($file) => str_contains($file->getFilename(), 'create_oauth_'))
            ->map->getPathname()
            ->values()
            ->all();

        return [
            '--path' => array_merge(
                $baseMigrations,
                [
                    module_path('Ad', 'database/migrations'),
                    module_path('Monetization', 'database/migrations'),
                ]
            ),
            '--realpath' => true,
        ];
    }
}
