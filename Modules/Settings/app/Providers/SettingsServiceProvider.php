<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Settings\Services\SettingService;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class, static fn () => new SettingService());

        $this->mergeConfigFrom($this->configPath(), 'settings');
    }

    public function boot(): void
    {
        $this->publishes([
            $this->configPath() => config_path('settings.php'),
        ], 'settings-config');

        $this->loadRoutesFrom($this->routesPath());
        $this->loadMigrationsFrom($this->migrationsPath());
    }

    private function configPath(): string
    {
        return __DIR__ . '/../../config/config.php';
    }

    private function routesPath(): string
    {
        return __DIR__ . '/../../routes/api.php';
    }

    private function migrationsPath(): string
    {
        return __DIR__ . '/../../database/migrations';
    }
}
