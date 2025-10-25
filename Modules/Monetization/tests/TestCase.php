<?php

namespace Modules\Monetization\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase {
        migrateFreshUsing as baseMigrateFreshUsing;
    }

    protected function migrateFreshUsing(): array
    {
        return array_merge(
            $this->baseMigrateFreshUsing(),
            ['--path' => $this->migrationPaths()]
        );
    }

    /**
     * @return array<int, string>
     */
    private function migrationPaths(): array
    {
        return array_merge(
            ['database/migrations'],
            array_map(
                fn (string $relativePath): string => $this->moduleRelativePath($relativePath),
                [
                    'database/migrations/2025_10_10_000001_create_plans_table.php',
                    'database/migrations/2025_10_10_000002_create_ad_plan_purchases_table.php',
                    'database/migrations/2025_10_10_000004_create_wallets_table.php',
                    'database/migrations/2025_10_10_000005_create_wallet_transactions_table.php',
                ],
            ),
        );
    }

    private function moduleRelativePath(string $relativePath): string
    {
        $absolute = module_path('Monetization', $relativePath);

        return ltrim(str_replace(base_path(), '', $absolute), DIRECTORY_SEPARATOR);
    }
}
