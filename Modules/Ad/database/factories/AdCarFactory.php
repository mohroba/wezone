<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdCar;

/**
 * @extends Factory<\Modules\Ad\Models\AdCar>
 */
class AdCarFactory extends Factory
{
    protected $model = AdCar::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'slug' => Str::slug(fake()->unique()->words(3, true)),
            'brand_id' => fake()->numberBetween(1, 200),
            'model_id' => fake()->numberBetween(1, 500),
            'year' => fake()->numberBetween(1390, 1403),
            'mileage' => fake()->numberBetween(1_000, 150_000),
            'fuel_type' => $fakerFa->randomElement(['بنزین', 'گازوئیل', 'دوگانه‌سوز']),
            'transmission' => $fakerFa->randomElement(['دنده‌ای', 'اتوماتیک']),
            'body_style' => $fakerFa->randomElement(['سدان', 'هاچ‌بک', 'شاسی‌بلند']),
            'color' => $fakerFa->safeColorName(),
            'condition' => $fakerFa->randomElement(['نو', 'دست دوم تمیز']),
            'ownership_count' => fake()->numberBetween(1, 3),
            'vin' => strtoupper(Str::random(17)),
            'registration_expiry' => now()->addMonths(fake()->numberBetween(1, 12)),
            'insurance_expiry' => now()->addMonths(fake()->numberBetween(1, 12)),
        ];
    }
}
