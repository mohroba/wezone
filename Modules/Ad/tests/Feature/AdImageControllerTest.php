<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdImageControllerTest extends TestCase
{
    use RefreshesAdDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
        config()->set('media-library.disk_name', 'public');
        Storage::fake('public');
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_owner_can_upload_multiple_images_with_metadata(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);

        Passport::actingAs($user);

        $response = $this->post(
            "/api/ads/{$ad->id}/images",
            [
                'images' => [
                    [
                        'file' => UploadedFile::fake()->image('front.jpg', 800, 600),
                        'alt' => 'Front view',
                        'caption' => 'Fresh detail',
                        'display_order' => 2,
                    ],
                    [
                        'file' => UploadedFile::fake()->image('rear.jpg', 800, 600),
                        'alt' => 'Rear view',
                    ],
                ],
            ],
            ['Accept' => 'application/json']
        );

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.alt', 'Front view')
            ->assertJsonPath('data.1.alt', 'Rear view')
            ->assertJsonPath('meta.message', 'Images uploaded successfully.');

        $ad->refresh();
        $this->assertCount(2, $ad->getMedia(Ad::COLLECTION_IMAGES));
    }

    public function test_list_endpoint_requires_authorization(): void
    {
        $owner = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $owner->id]);
        $unauthorized = User::factory()->create();

        Passport::actingAs($unauthorized);

        $this->getJson("/api/ads/{$ad->id}/images")
            ->assertForbidden();
    }

    public function test_can_list_images_sorted_by_display_order(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);

        $first = $ad->addMedia(UploadedFile::fake()->image('first.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);
        $first->setCustomProperty('display_order', 3);
        $first->save();

        $second = $ad->addMedia(UploadedFile::fake()->image('second.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);
        $second->setCustomProperty('display_order', 1);
        $second->save();

        Passport::actingAs($user);

        $this->getJson("/api/ads/{$ad->id}/images")
            ->assertOk()
            ->assertJsonPath('data.0.id', $second->id)
            ->assertJsonPath('data.1.id', $first->id);
    }

    public function test_can_update_image_metadata(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);
        $media = $ad->addMedia(UploadedFile::fake()->image('car.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        Passport::actingAs($user);

        $this->postJson(
            "/api/ads/{$ad->id}/images/{$media->id}/update",
            [
                'alt' => 'Left side view',
                'caption' => 'Brand new tires',
                'display_order' => 5,
            ]
        )
            ->assertOk()
            ->assertJsonPath('data.alt', 'Left side view')
            ->assertJsonPath('data.display_order', 5)
            ->assertJsonPath('meta.message', 'Image metadata updated successfully.');
    }

    public function test_can_reorder_images(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $user->id]);
        $one = $ad->addMedia(UploadedFile::fake()->image('one.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);
        $two = $ad->addMedia(UploadedFile::fake()->image('two.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        Passport::actingAs($user);

        $payload = [
            'order' => [
                ['media_id' => $one->id, 'display_order' => 5],
                ['media_id' => $two->id, 'display_order' => 1],
            ],
        ];

        $this->postJson("/api/ads/{$ad->id}/images/reorder", $payload)
            ->assertOk()
            ->assertJsonPath('data.0.id', $two->id)
            ->assertJsonPath('data.0.display_order', 1)
            ->assertJsonPath('data.1.display_order', 5);
    }

    public function test_can_delete_image_as_moderator(): void
    {
        $owner = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $owner->id]);
        $media = $ad->addMedia(UploadedFile::fake()->image('delete.jpg', 800, 600))
            ->toMediaCollection(Ad::COLLECTION_IMAGES);

        $moderator = User::factory()->create();
        $permission = Permission::firstOrCreate([
            'name' => 'ad.report.manage',
            'guard_name' => 'api',
        ]);
        $moderator->givePermissionTo($permission);

        Passport::actingAs($moderator);

        $this->deleteJson("/api/ads/{$ad->id}/images/{$media->id}")
            ->assertOk()
            ->assertJsonPath('meta.message', 'Image deleted successfully.');

        $this->assertCount(0, $ad->fresh()->getMedia(Ad::COLLECTION_IMAGES));
    }
}
