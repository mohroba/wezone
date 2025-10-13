<?php

namespace Database\Factories;

use App\Models\KpiDevice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\KpiDevice>
 */
class KpiDeviceFactory extends Factory
{
    protected $model = KpiDevice::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'device_uuid' => (string) Str::uuid(),
            'platform' => fake()->randomElement(['android', 'ios']),
            'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
            'os_version' => fake()->randomElement(['14', '15', '16']),
            'device_model' => $fakerFa->randomElement(['پیکسل ۸', 'گلکسی اس ۲۴', 'آیفون ۱۵']),
            'device_manufacturer' => $fakerFa->randomElement(['گوگل', 'سامسونگ', 'اپل']),
            'locale' => 'fa_IR',
            'timezone' => 'Asia/Tehran',
            'push_token' => Str::random(32),
            'first_seen_at' => now()->subDays(fake()->numberBetween(1, 10)),
            'last_seen_at' => now()->subHours(fake()->numberBetween(1, 72)),
            'last_heartbeat_at' => now()->subMinutes(fake()->numberBetween(1, 120)),
            'is_active' => true,
            'extra' => ['منبع' => $fakerFa->word()],
        ];
    }
}
