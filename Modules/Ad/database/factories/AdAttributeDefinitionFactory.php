<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;

/**
 * @extends Factory<\Modules\Ad\Models\AdAttributeDefinition>
 */
class AdAttributeDefinitionFactory extends Factory
{
    protected $model = AdAttributeDefinition::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');
        $label = $fakerFa->unique()->words(2, true);

        return [
            'group_id' => AdAttributeGroup::factory(),
            'key' => Str::slug($label),
            'label' => $label,
            'help_text' => $fakerFa->optional()->sentence(),
            'data_type' => fake()->randomElement(['string', 'integer', 'boolean']),
            'unit' => $fakerFa->optional()->word(),
            'options' => [],
            'is_required' => fake()->boolean(),
            'is_filterable' => fake()->boolean(),
            'is_searchable' => fake()->boolean(),
            'validation_rules' => null,
        ];
    }
}
