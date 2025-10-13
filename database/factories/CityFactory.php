<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'province' => Province::factory(),
            'name' => $fakerFa->city(),
            'name_en' => Str::title(fake()->city()),
            'latitude' => fake()->latitude(24, 40),
            'longitude' => fake()->longitude(44, 64),
        ];
    }
}
