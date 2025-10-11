<?php

namespace Modules\Ad\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdRealEstate;
use Tests\TestCase;

class AdvertisableTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', Str::random(32));
    }

    public function test_index_returns_registered_types_with_attribute_metadata(): void
    {
        $carGroup = AdAttributeGroup::create([
            'name' => 'Performance',
            'advertisable_type' => AdCar::class,
            'display_order' => 1,
        ]);

        AdAttributeDefinition::create([
            'group_id' => $carGroup->id,
            'key' => 'horsepower',
            'label' => 'Horsepower',
            'data_type' => 'integer',
            'help_text' => 'Engine power output.',
            'unit' => 'hp',
            'options' => null,
            'is_required' => false,
            'is_filterable' => true,
            'is_searchable' => true,
            'validation_rules' => 'integer|min:0',
        ]);

        $realEstateGroup = AdAttributeGroup::create([
            'name' => 'Amenities',
            'advertisable_type' => AdRealEstate::class,
            'display_order' => 1,
        ]);

        AdAttributeDefinition::create([
            'group_id' => $realEstateGroup->id,
            'key' => 'has_pool',
            'label' => 'Swimming Pool',
            'data_type' => 'boolean',
            'help_text' => null,
            'unit' => null,
            'options' => null,
            'is_required' => false,
            'is_filterable' => true,
            'is_searchable' => false,
            'validation_rules' => 'boolean',
        ]);

        $response = $this->getJson('/api/advertisable-types');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.key', 'car');
        $response->assertJsonPath('data.0.base_properties.0.name', 'slug');
        $response->assertJsonPath('data.0.attribute_groups.0.definitions.0.key', 'horsepower');
        $response->assertJsonPath('data.1.key', 'real_estate');
        $response->assertJsonPath('data.1.attribute_groups.0.definitions.0.key', 'has_pool');
        $response->assertJsonPath('data.2.key', 'job');
    }

    public function test_show_returns_single_type_payload(): void
    {
        AdAttributeGroup::create([
            'name' => 'Compensation',
            'advertisable_type' => AdJob::class,
            'display_order' => 2,
        ]);

        $response = $this->getJson('/api/advertisable-types/job');

        $response->assertOk();
        $response->assertJsonPath('data.key', 'job');
        $response->assertJsonPath('data.model_class', AdJob::class);
        $response->assertJsonPath('data.base_properties.0.name', 'slug');
    }

    public function test_show_returns_not_found_for_unknown_type(): void
    {
        $this->getJson('/api/advertisable-types/unknown')->assertNotFound();
    }
}
