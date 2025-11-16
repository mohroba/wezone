<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdvertisableType;

class AdvertisableTypeFactory extends Factory
{
    protected $model = AdvertisableType::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'label' => $this->faker->words(2, true),
            'model_class' => AdCar::class,
            'description' => $this->faker->sentence(),
        ];
    }
}
