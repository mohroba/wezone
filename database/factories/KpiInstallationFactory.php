<?php

namespace Database\Factories;

use App\Models\KpiDevice;
use App\Models\KpiInstallation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\KpiInstallation>
 */
class KpiInstallationFactory extends Factory
{
    protected $model = KpiInstallation::class;

    public function definition(): array
    {
        return [
            'kpi_device_id' => KpiDevice::factory(),
            'installed_at' => now()->subDays(fake()->numberBetween(1, 10)),
            'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
            'platform' => fake()->randomElement(['android', 'ios']),
            'is_reinstall' => false,
        ];
    }
}
