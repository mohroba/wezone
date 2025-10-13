<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Province>
 */
class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'country' => Country::factory(),
            'name' => $fakerFa->city(),
            'name_en' => Str::title(fake()->city()),
            'latitude' => fake()->latitude(24, 40),
            'longitude' => fake()->longitude(44, 64),
        ];
    }
}
