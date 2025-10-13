<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdCategory;

/**
 * @extends Factory<\Modules\Ad\Models\AdAttributeGroup>
 */
class AdAttributeGroupFactory extends Factory
{
    protected $model = AdAttributeGroup::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'name' => $fakerFa->words(2, true),
            'advertisable_type' => AdCar::class,
            'category_id' => AdCategory::factory(),
            'display_order' => fake()->numberBetween(1, 10),
        ];
    }
}
