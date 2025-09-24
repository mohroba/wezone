<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Profile;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->date(),
            'national_id' => fake()->unique()->numerify('##########'),
            'residence_city_id' => fake()->numberBetween(1, 1000),
            'residence_province_id' => fake()->numberBetween(1, 1000),
        ];
    }
}
