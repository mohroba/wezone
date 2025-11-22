<?php

namespace Modules\Ad\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdvertisableType;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'advertisable_type_id' => AdvertisableType::factory()->state([
                'model_class' => AdCar::class,
            ]),
            'advertisable_type' => AdCar::class,
            'advertisable_id' => AdCar::factory(),
            'slug' => $this->faker->unique()->slug(),
            'title' => $this->faker->sentence(6),
            'subtitle' => $this->faker->optional()->sentence(8),
            'description' => $this->faker->paragraph(),
            'status' => 'draft',
            'published_at' => null,
            'expires_at' => null,
            'price_amount' => $this->faker->numberBetween(1000, 100000),
            'price_currency' => 'USD',
            'is_negotiable' => $this->faker->boolean(),
            'is_exchangeable' => $this->faker->boolean(),
            'comment_enable' => true,
            'phone_enable' => true,
            'chat_enable' => true,
            'extra_amount' => $this->faker->numberBetween(0, 500000),
            'exchange_description' => $this->faker->optional()->sentence(8),
            'city_id' => null,
            'province_id' => null,
            'latitude' => null,
            'longitude' => null,
            'contact_channel' => ['phone' => $this->faker->phoneNumber()],
            'view_count' => 0,
            'share_count' => 0,
            'favorite_count' => 0,
            'like_count' => 0,
            'featured_until' => null,
            'priority_score' => 0,
        ];
    }
}
