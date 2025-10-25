<?php

declare(strict_types=1);

namespace Modules\Notification\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_reading_and_deleting_notifications(): void
    {
        $user = User::factory()->create();
        $notification = DatabaseNotification::query()->create([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\GenericNotification',
            'notifiable_id' => $user->id,
            'notifiable_type' => User::class,
            'data' => ['message' => 'Sample notification'],
        ]);

        Passport::actingAs($user);

        $listResponse = $this->getJson('/api/notifications');
        $listResponse->assertOk();
        $listResponse->assertJsonPath('data.0.id', $notification->id);

        $readResponse = $this->postJson("/api/notifications/{$notification->id}/read");
        $readResponse->assertOk();
        $this->assertNotNull($notification->fresh()->read_at);

        $deleteResponse = $this->deleteJson("/api/notifications/{$notification->id}");
        $deleteResponse->assertOk();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}
