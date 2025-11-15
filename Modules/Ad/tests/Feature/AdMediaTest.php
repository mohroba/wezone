<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdAttributeDefinition;
use Modules\Ad\Models\AdAttributeGroup;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Tests\TestCase;

class AdMediaTest extends TestCase
{
    use RefreshesAdDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', Str::random(32));
        config()->set('media-library.disk_name', 'public');
        Storage::fake('public');
    }

    public function test_ad_creation_persists_uploaded_images_and_returns_media_payload(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext();
        $carAttributes = [
            'brand_id' => 10,
            'model_id' => 20,
            'year' => 2024,
        ];

        $response = $this->post('/api/ads', [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => $carAttributes,
            ],
            'slug' => 'ad-'.Str::random(8),
            'title' => 'Brand new car',
            'is_negotiable' => false,
            'is_exchangeable' => false,
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Red'],
            ],
            'images' => [
                ['file' => UploadedFile::fake()->image('front.jpg', 800, 600)],
                ['file' => UploadedFile::fake()->image('rear.jpg', 800, 600)],
            ],
        ], ['Accept' => 'application/json']);

        $response->assertCreated();
        $response->assertJsonCount(2, 'data.images');

        /** @var Ad $ad */
        $ad = Ad::query()->first();

        $this->assertNotNull($ad);
        $this->assertInstanceOf(AdCar::class, $ad->advertisable);
        $this->assertSame($carAttributes['brand_id'], $ad->advertisable->brand_id);
        $this->assertSame($carAttributes['model_id'], $ad->advertisable->model_id);
        $this->assertSame($carAttributes['year'], (int) $ad->advertisable->year);
        $mediaItems = $ad->getMedia(Ad::COLLECTION_IMAGES);
        $this->assertCount(2, $mediaItems);

        $responseIds = collect($response->json('data.images'))->pluck('id')->all();
        $this->assertSame($mediaItems->pluck('id')->all(), $responseIds);

        foreach ($mediaItems as $media) {
            Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
            Storage::disk('public')->assertExists($media->getPathRelativeToRoot(Ad::CONVERSION_THUMB));
            Storage::disk('public')->assertExists($media->getPathRelativeToRoot(Ad::CONVERSION_MEDIUM));
        }
    }

    public function test_ad_creation_accepts_plain_file_array_images(): void
    {
        $user = User::factory()->create();
        $context = $this->prepareAdvertisableContext();
        $carAttributes = [
            'brand_id' => 8,
            'model_id' => 21,
            'year' => 2023,
        ];

        $response = $this->post('/api/ads', [
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable' => [
                'type' => AdCar::class,
                'attributes' => $carAttributes,
            ],
            'slug' => 'ad-'.Str::random(8),
            'title' => 'Compact car',
            'is_negotiable' => false,
            'is_exchangeable' => false,
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Blue'],
            ],
            'images' => [
                ['file' => UploadedFile::fake()->image('side.jpg', 800, 600)],
                ['file' => UploadedFile::fake()->image('interior.jpg', 800, 600)],
            ],
        ], ['Accept' => 'application/json']);

        $response->assertCreated();
        $response->assertJsonCount(2, 'data.images');
    }

    public function test_ad_update_reorders_and_deletes_images(): void
    {
        $user = User::factory()->create();
        $slug = 'ad-'.Str::random(8);
        $car = AdCar::create([
            'slug' => $slug,
            'brand_id' => 10,
            'model_id' => 11,
            'year' => 2020,
        ]);

        $context = $this->prepareAdvertisableContext();
        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $slug,
            'title' => 'Used car',
        ]);

        $existingOne = $ad->addMedia(UploadedFile::fake()->image('existing-front.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);
        $existingTwo = $ad->addMedia(UploadedFile::fake()->image('existing-back.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        $updateResponse = $this->post("/api/ads/{$ad->id}/update", [
            'advertisable_type_id' => $context['type']->id,
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Updated'],
            ],
            'is_negotiable' => false,
            'is_exchangeable' => false,
            'images' => [
                [
                    'id' => $existingOne->id,
                    'custom_properties' => ['alt' => 'Front view'],
                ],
                [
                    'file' => UploadedFile::fake()->image('new-interior.jpg', 800, 600),
                    'custom_properties' => ['alt' => 'Interior'],
                ],
            ],
        ], ['Accept' => 'application/json']);

        $updateResponse->assertOk();
        $updateResponse->assertJsonCount(2, 'data.images');
        $updateResponse->assertJsonPath('data.images.0.id', $existingOne->id);
        $updateResponse->assertJsonPath('data.images.0.custom_properties.alt', 'Front view');
        $updateResponse->assertJsonPath('data.images.1.custom_properties.alt', 'Interior');

        $ad->refresh();
        $mediaItems = $ad->getMedia(Ad::COLLECTION_IMAGES);
        $this->assertCount(2, $mediaItems);

        $orderedIds = $mediaItems->pluck('id')->all();
        $this->assertSame($existingOne->id, $orderedIds[0]);
        $this->assertNotContains($existingTwo->id, $orderedIds);

        $newMedia = $mediaItems->firstWhere('id', '!=', $existingOne->id);
        $this->assertNotNull($newMedia);
        Storage::disk('public')->assertExists($newMedia->getPathRelativeToRoot());
        Storage::disk('public')->assertExists($newMedia->getPathRelativeToRoot(Ad::CONVERSION_THUMB));

        Storage::disk('public')->assertMissing($existingTwo->getPathRelativeToRoot());
        $this->assertDatabaseMissing('media', ['id' => $existingTwo->id]);
    }

    public function test_ad_update_accepts_plain_file_array_images(): void
    {
        $user = User::factory()->create();
        $slug = 'ad-'.Str::random(8);
        $car = AdCar::create([
            'slug' => $slug,
            'brand_id' => 12,
            'model_id' => 13,
            'year' => 2021,
        ]);

        $context = $this->prepareAdvertisableContext();
        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $slug,
            'title' => 'Family car',
        ]);

        $existing = $ad->addMedia(UploadedFile::fake()->image('existing-front.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        $response = $this->post("/api/ads/{$ad->id}/update", [
            'advertisable_type_id' => $context['type']->id,
            'categories' => [
                ['id' => $context['category']->id, 'is_primary' => true],
            ],
            'attribute_values' => [
                ['definition_id' => $context['definition']->id, 'value_string' => 'Silver'],
            ],
            'images' => [
                ['id' => $existing->id],
                ['file' => UploadedFile::fake()->image('new-exterior.jpg', 800, 600)],
            ],
        ], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertJsonCount(2, 'data.images');
        $response->assertJsonPath('data.images.0.id', $existing->id);
    }

    public function test_can_append_images_using_dedicated_endpoint(): void
    {
        $user = User::factory()->create();
        $slug = 'ad-'.Str::random(8);
        $context = $this->prepareAdvertisableContext();
        $car = AdCar::create([
            'slug' => $slug,
            'brand_id' => 9,
            'model_id' => 18,
            'year' => 2022,
        ]);

        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $slug,
            'title' => 'City car',
        ]);

        $existing = $ad->addMedia(UploadedFile::fake()->image('existing.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        $response = $this->post("/api/ads/{$ad->id}/images", [
            'images' => [
                ['file' => UploadedFile::fake()->image('additional-1.jpg', 800, 600)],
                [
                    'file' => UploadedFile::fake()->image('additional-2.jpg', 800, 600),
                    'custom_properties' => ['alt' => 'Rear exterior'],
                ],
            ],
        ], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertJsonCount(3, 'data.images');
        $response->assertJsonPath('data.images.0.id', $existing->id);
        $response->assertJsonPath('data.images.2.custom_properties.alt', 'Rear exterior');

        $ad->refresh();
        $mediaItems = $ad->getMedia(Ad::COLLECTION_IMAGES);

        $this->assertCount(3, $mediaItems);
        $this->assertSame($existing->id, $mediaItems->first()->id);

        $newItems = $mediaItems->slice(1)->values();
        $this->assertCount(2, $newItems);

        foreach ($newItems as $media) {
            Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
            Storage::disk('public')->assertExists($media->getPathRelativeToRoot(Ad::CONVERSION_THUMB));
        }
    }

    public function test_can_append_images_using_plain_file_array(): void
    {
        $user = User::factory()->create();
        $slug = 'ad-'.Str::random(8);
        $context = $this->prepareAdvertisableContext();
        $car = AdCar::create([
            'slug' => $slug,
            'brand_id' => 7,
            'model_id' => 14,
            'year' => 2020,
        ]);

        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type_id' => $context['type']->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => $slug,
            'title' => 'City hatchback',
        ]);

        $response = $this->post("/api/ads/{$ad->id}/images", [
            'images' => [
                ['file' => UploadedFile::fake()->image('front-extra.jpg', 800, 600)],
                ['file' => UploadedFile::fake()->image('rear-extra.jpg', 800, 600)],
            ],
        ], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertJsonCount(2, 'data.images');
    }

    private function prepareAdvertisableContext(): array
    {
        $type = AdvertisableType::factory()->create([
            'key' => 'media-type-'.Str::random(4),
            'label' => 'Media Type',
            'model_class' => AdCar::class,
        ]);

        $category = AdCategory::create([
            'slug' => 'media-category-'.Str::random(4),
            'name' => 'Media Category',
            'advertisable_type_id' => $type->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $group = AdAttributeGroup::create([
            'advertisable_type_id' => $type->id,
            'name' => 'Meta',
            'display_order' => 1,
        ]);

        $definition = AdAttributeDefinition::create([
            'attribute_group_id' => $group->id,
            'key' => 'media_attr_'.Str::random(3),
            'label' => 'Media Attribute',
            'data_type' => 'string',
            'is_required' => false,
            'is_filterable' => false,
            'is_searchable' => false,
        ]);

        return compact('type', 'category', 'definition');
    }
}
