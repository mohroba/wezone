<?php

namespace Modules\Ad\Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCar;

/**
 * @extends Factory<\Modules\Ad\Models\Ad>
 */
class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');
        $title = $fakerFa->sentence(3);
        $city = City::query()->inRandomOrder()->first();
        $provinceId = $city?->province;

        return [
            'user_id' => User::factory(),
            'advertisable_type' => AdCar::class,
            'advertisable_id' => AdCar::factory(),
            'slug' => Str::slug(fake()->unique()->words(3, true)),
            'title' => $title,
            'subtitle' => $fakerFa->optional()->sentence(4),
            'description' => $fakerFa->paragraph(),
            'status' => fake()->randomElement(['draft', 'published']),
            'published_at' => now()->subDays(fake()->numberBetween(1, 10)),
            'expires_at' => now()->addDays(fake()->numberBetween(10, 60)),
            'price_amount' => fake()->numberBetween(5_000_000, 900_000_000),
            'price_currency' => 'IRR',
            'is_negotiable' => $this->faker->boolean(),
            'is_exchangeable' => $this->faker->boolean(),
            'city_id' => $city?->id,
            'province_id' => $provinceId,
            'latitude' => $city?->latitude,
            'longitude' => $city?->longitude,
            'contact_channel' => ['تلفن' => $fakerFa->phoneNumber()],
            'view_count' => fake()->numberBetween(0, 1_000),
            'share_count' => fake()->numberBetween(0, 300),
            'favorite_count' => fake()->numberBetween(0, 500),
            'featured_until' => now()->addDays(fake()->numberBetween(1, 30)),
            'priority_score' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
