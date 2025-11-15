<?php

namespace Modules\Ad\Tests\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshesAdDatabase
{
    use RefreshDatabase {
        migrateDatabases as baseMigrateDatabases;
    }

    protected function migrateDatabases(): void
    {
        $this->baseMigrateDatabases();

        $this->artisan('migrate', [
            '--path' => base_path('Modules/Ad/database/migrations'),
            '--realpath' => true,
            '--force' => true,
        ]);
    }
}
