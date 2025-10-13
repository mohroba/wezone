<?php

namespace Database\Factories;

use App\Models\KpiDevice;
use App\Models\KpiEvent;
use App\Models\KpiSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\KpiEvent>
 */
class KpiEventFactory extends Factory
{
    protected $model = KpiEvent::class;

    public function definition(): array
    {
        return [
            'kpi_device_id' => KpiDevice::factory(),
            'kpi_session_id' => KpiSession::factory(),
            'event_uuid' => (string) Str::uuid(),
            'event_key' => fake()->randomElement(['ورود', 'مشاهده', 'کلیک']),
            'event_value' => fake()->optional()->numberBetween(1, 100),
            'occurred_at' => now()->subSeconds(fake()->numberBetween(10, 3600)),
            'metadata' => ['یادداشت' => fake('fa_IR')->sentence()],
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (KpiEvent $event): void {
                if ($event->session && $event->session->kpi_device_id) {
                    $event->kpi_device_id = $event->session->kpi_device_id;
                }
            })
            ->afterCreating(function (KpiEvent $event): void {
                if ($event->session && $event->session->kpi_device_id !== $event->kpi_device_id) {
                    $event->forceFill(['kpi_device_id' => $event->session->kpi_device_id])->save();
                }
            });
    }
}
