<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdCar;
use Tests\TestCase;

class AdMediaTest extends TestCase
{
    use RefreshDatabase;

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
        $car = AdCar::create(['slug' => 'car-'.Str::random(8)]);

        $response = $this->post('/api/ads', [
            'user_id' => $user->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => 'ad-'.Str::random(8),
            'title' => 'Brand new car',
            'is_negotiable' => false,
            'is_exchangeable' => false,
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

    public function test_ad_update_reorders_and_deletes_images(): void
    {
        $user = User::factory()->create();
        $car = AdCar::create(['slug' => 'car-'.Str::random(8)]);

        $ad = Ad::create([
            'user_id' => $user->id,
            'advertisable_type' => AdCar::class,
            'advertisable_id' => $car->id,
            'slug' => 'ad-'.Str::random(8),
            'title' => 'Used car',
        ]);

        $existingOne = $ad->addMedia(UploadedFile::fake()->image('existing-front.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);
        $existingTwo = $ad->addMedia(UploadedFile::fake()->image('existing-back.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        $updateResponse = $this->post("/api/ads/{$ad->id}/update", [
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
}
