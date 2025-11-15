<?php

namespace Modules\Ad\Tests\Feature;

use Illuminate\Support\Str;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Tests\TestCase;

class AdvertisableTypeControllerTest extends TestCase
{
    use RefreshesAdDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', Str::random(32));
    }

    public function test_index_returns_registered_types_with_attribute_metadata(): void
    {
        $carType = AdvertisableType::where('key', 'car')->firstOrFail();
        $realEstateType = AdvertisableType::where('key', 'real_estate')->firstOrFail();

        $carGroup = AdAttributeGroup::create([
            'name' => 'Performance',
            'advertisable_type_id' => $carType->id,
            'display_order' => 1,
        ]);

        AdAttributeDefinition::create([
            'attribute_group_id' => $carGroup->id,
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
            'advertisable_type_id' => $realEstateType->id,
            'display_order' => 1,
        ]);

        AdAttributeDefinition::create([
            'attribute_group_id' => $realEstateGroup->id,
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

        $payload = collect($response->json('data'));

        $carPayload = $payload->firstWhere('key', 'car');
        $this->assertNotNull($carPayload);
        $this->assertSame('horsepower', data_get($carPayload, 'attribute_groups.0.definitions.0.key'));

        $realEstatePayload = $payload->firstWhere('key', 'real_estate');
        $this->assertNotNull($realEstatePayload);
        $this->assertSame('has_pool', data_get($realEstatePayload, 'attribute_groups.0.definitions.0.key'));
    }

    public function test_show_returns_single_type_payload(): void
    {
        $jobType = AdvertisableType::where('key', 'job')->firstOrFail();

        AdAttributeGroup::create([
            'name' => 'Compensation',
            'advertisable_type_id' => $jobType->id,
            'display_order' => 2,
        ]);

        $response = $this->getJson('/api/advertisable-types/job');

        $response->assertOk();
        $response->assertJsonPath('data.key', 'job');
        $response->assertJsonPath('data.model_class', $jobType->model_class);
    }

    public function test_show_returns_not_found_for_unknown_type(): void
    {
        $this->getJson('/api/advertisable-types/unknown')->assertNotFound();
    }
}
