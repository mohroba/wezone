<?php

namespace Modules\Monetization\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\AdvertisableType;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;

/**
 * @extends Factory<PlanPriceOverride>
 */
class PlanPriceOverrideFactory extends Factory
{
    protected $model = PlanPriceOverride::class;

    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'advertisable_type_id' => AdvertisableType::factory(),
            'ad_category_id' => null,
            'override_price' => $this->faker->randomFloat(2, 1000, 9000),
            'currency' => 'IRR',
            'discount_type' => 'percent',
            'discount_value' => $this->faker->numberBetween(5, 20),
            'discount_starts_at' => now()->subDay(),
            'discount_ends_at' => now()->addDays(5),
            'usage_cap' => 100,
            'usage_count' => 0,
            'is_stackable' => false,
            'metadata' => ['notes' => $this->faker->sentence()],
        ];
    }

    public function withFixedDiscount(float $value): self
    {
        return $this->state(fn () => [
            'discount_type' => 'fixed',
            'discount_value' => $value,
        ]);
    }
}
