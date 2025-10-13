<?php

namespace Database\Factories;

use App\Models\KpiDevice;
use App\Models\KpiSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\KpiSession>
 */
class KpiSessionFactory extends Factory
{
    protected $model = KpiSession::class;

    public function definition(): array
    {
        $startedAt = now()->subMinutes(fake()->numberBetween(10, 120));
        $endedAt = (clone $startedAt)->addMinutes(fake()->numberBetween(1, 60));

        return [
            'session_uuid' => (string) Str::uuid(),
            'kpi_device_id' => KpiDevice::factory(),
            'user_id' => User::factory(),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_seconds' => $endedAt->diffInSeconds($startedAt),
            'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
            'platform' => fake()->randomElement(['android', 'ios']),
            'os_version' => fake()->randomElement(['14', '15', '16']),
            'network_type' => fake()->randomElement(['wifi', '4g', '5g']),
            'city' => fake('fa_IR')->city(),
            'country' => fake('fa_IR')->country(),
            'metadata' => ['منبع' => fake('fa_IR')->word()],
        ];
    }
}
