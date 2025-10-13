<?php

namespace Modules\Ad\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeValue;
use Modules\Ad\Models\AdCar;

/**
 * @extends Factory<\Modules\Ad\Models\AdAttributeValue>
 */
class AdAttributeValueFactory extends Factory
{
    protected $model = AdAttributeValue::class;

    public function definition(): array
    {
        $fakerFa = fake('fa_IR');

        return [
            'definition_id' => AdAttributeDefinition::factory(),
            'advertisable_type' => AdCar::class,
            'advertisable_id' => AdCar::factory(),
            'value_string' => $fakerFa->words(2, true),
            'value_integer' => null,
            'value_decimal' => null,
            'value_boolean' => null,
            'value_json' => null,
            'normalized_value' => null,
        ];
    }
}
