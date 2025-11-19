<?php

namespace Modules\Ad\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdRealEstate;
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
        $this->assertSame($carType->id, data_get($carPayload, 'id'));
        $this->assertSame('horsepower', data_get($carPayload, 'attribute_groups.0.definitions.0.key'));
        $this->assertArrayNotHasKey('base_properties', $carPayload);

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
        $response->assertJsonPath('data.id', $jobType->id);
        $response->assertArrayNotHasKey('base_properties', $response->json('data'));
    }

    public function test_show_returns_not_found_for_unknown_type(): void
    {
        $this->getJson('/api/advertisable-types/unknown')->assertNotFound();
    }

    public function test_store_creates_advertisable_type_with_icon(): void
    {
        Storage::fake('public');
        config()->set('media-library.disk_name', 'public');

        $response = $this->postJson('/api/advertisable-types', [
            'key' => 'electronics',
            'label' => 'Electronics',
            'description' => 'Consumer electronics listings.',
            'model_class' => AdCar::class,
            'icon' => UploadedFile::fake()->image('icon.png', 100, 100),
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.key', 'electronics');
        $this->assertIsString($response->json('data.icon_url'));

        $this->assertDatabaseHas('advertisable_types', [
            'key' => 'electronics',
            'label' => 'Electronics',
            'model_class' => AdCar::class,
        ]);

        $type = AdvertisableType::where('key', 'electronics')->first();
        $this->assertNotNull($type);
        $this->assertNotNull($type->getFirstMedia(AdvertisableType::COLLECTION_ICON));
    }

    public function test_update_modifies_metadata_and_icon(): void
    {
        Storage::fake('public');
        config()->set('media-library.disk_name', 'public');

        $type = AdvertisableType::factory()->create([
            'key' => 'vehicles',
            'label' => 'Vehicles',
            'model_class' => AdCar::class,
        ]);

        $response = $this->postJson("/api/advertisable-types/{$type->id}/update", [
            'label' => 'Updated Vehicles',
            'description' => 'Updated description',
            'model_class' => AdRealEstate::class,
            'icon' => UploadedFile::fake()->image('updated.png', 150, 150),
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.label', 'Updated Vehicles');
        $response->assertJsonPath('data.model_class', AdRealEstate::class);

        $this->assertDatabaseHas('advertisable_types', [
            'id' => $type->id,
            'label' => 'Updated Vehicles',
            'model_class' => AdRealEstate::class,
        ]);

        $type->refresh();
        $this->assertNotNull($type->getFirstMedia(AdvertisableType::COLLECTION_ICON));
    }

    public function test_destroy_deletes_advertisable_type(): void
    {
        $type = AdvertisableType::factory()->create();

        $this->postJson("/api/advertisable-types/{$type->id}/delete")
            ->assertNoContent();

        $this->assertModelMissing($type);
    }
}
