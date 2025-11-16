<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdCar;

class AdCarFactory extends Factory
{
    protected $model = AdCar::class;

    public function definition(): array
    {
        return [
            'slug' => Str::slug($this->faker->unique()->sentence(3)),
            'brand_id' => $this->faker->numberBetween(1, 50),
            'model_id' => $this->faker->numberBetween(1, 200),
            'year' => $this->faker->numberBetween(1995, (int) now()->year),
            'mileage' => $this->faker->numberBetween(5000, 200000),
            'fuel_type' => $this->faker->randomElement(['gasoline', 'diesel', 'electric']),
            'transmission' => $this->faker->randomElement(['manual', 'automatic']),
            'body_style' => $this->faker->randomElement(['sedan', 'hatchback', 'suv']),
            'color' => $this->faker->safeColorName(),
            'condition' => $this->faker->randomElement(['new', 'used']),
            'ownership_count' => $this->faker->numberBetween(1, 3),
            'vin' => strtoupper($this->faker->bothify('?????????????????')),
            'registration_expiry' => $this->faker->optional()->date(),
            'insurance_expiry' => $this->faker->optional()->date(),
        ];
    }
}
