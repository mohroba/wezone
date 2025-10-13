<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'capital_city' => null,
            'name' => $fakerFa->country(),
            'name_en' => Str::title(fake()->country()),
        ];
    }
}
