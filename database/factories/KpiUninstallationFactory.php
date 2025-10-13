<?php

namespace Database\Factories;

use App\Models\KpiDevice;
use App\Models\KpiUninstallation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\KpiUninstallation>
 */
class KpiUninstallationFactory extends Factory
{
    protected $model = KpiUninstallation::class;

    public function definition(): array
    {
        return [
            'kpi_device_id' => KpiDevice::factory(),
            'user_id' => null,
            'uninstalled_at' => now()->subHours(fake()->numberBetween(1, 72)),
            'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
            'reason' => fake('fa_IR')->randomElement(['حذف دستی', 'عدم نیاز', 'خطای برنامه']),
            'report_source' => fake('fa_IR')->randomElement(['کاربر', 'سیستم']),
            'metadata' => ['توضیح' => fake('fa_IR')->optional()->sentence()],
        ];
    }
}
