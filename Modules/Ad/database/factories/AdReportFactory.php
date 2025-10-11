<?php

namespace Modules\Ad\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdReport;

class AdReportFactory extends Factory
{
    protected $model = AdReport::class;

    public function definition(): array
    {
        return [
            'ad_id' => Ad::factory(),
            'reported_by' => User::factory(),
            'reason_code' => $this->faker->randomElement(['spam', 'fraud', 'duplicate']),
            'description' => $this->faker->sentence(),
            'status' => 'pending',
            'handled_by' => null,
            'handled_at' => null,
            'resolution_notes' => null,
            'metadata' => null,
        ];
    }
}
