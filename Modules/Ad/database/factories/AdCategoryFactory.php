<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdvertisableType;

/**
 * @extends Factory<AdCategory>
 */
class AdCategoryFactory extends Factory
{
    protected $model = AdCategory::class;

    public function definition(): array
    {
        $advertisableType = AdvertisableType::factory();

        return [
            'advertisable_type_id' => $advertisableType,
            'parent_id' => null,
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->words(2, true),
            'name_localized' => [],
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
