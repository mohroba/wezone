<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdCategory;

/**
 * @extends Factory<\Modules\Ad\Models\AdCategory>
 */
class AdCategoryFactory extends Factory
{
    protected $model = AdCategory::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');
        $name = $fakerFa->unique()->words(2, true);
        $slug = Str::slug($name);

        return [
            'parent_id' => null,
            'depth' => 0,
            'path' => $slug,
            'slug' => $slug,
            'name' => $name,
            'name_localized' => ['fa' => $name],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 100),
            'filters_schema' => [],
        ];
    }
}
