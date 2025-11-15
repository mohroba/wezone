<?php

namespace Tests\Feature\Ad;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdConversation;
use Tests\TestCase;

class AdConversationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_chat_on_ads_and_hide_conversations(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $ad = Ad::factory()->for($seller, 'user')->create();

        Passport::actingAs($buyer);

        $createResponse = $this->postJson("/api/ads/{$ad->id}/conversations", [
            'message' => 'Is this still available?',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.ad_id', $ad->id)
            ->assertJsonPath('data.latest_message.body', 'Is this still available?')
            ->assertJsonPath('data.latest_message.type', 'text');

        $conversationId = $createResponse->json('data.id');

        $this->assertDatabaseHas('ad_conversations', [
            'id' => $conversationId,
            'ad_id' => $ad->id,
            'initiated_by' => $buyer->id,
        ]);

        $this->assertDatabaseHas('ad_conversation_user', [
            'ad_conversation_id' => $conversationId,
            'user_id' => $buyer->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('ad_conversation_user', [
            'ad_conversation_id' => $conversationId,
            'user_id' => $seller->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('ad_messages', [
            'ad_conversation_id' => $conversationId,
            'user_id' => $buyer->id,
            'body' => 'Is this still available?',
            'type' => 'text',
        ]);

        $this->assertSame(1, AdConversation::count());
        $this->assertSame(2, AdConversation::first()->participants()->count());

        $this->getJson('/api/ads/conversations')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationId);

        $this->postJson("/api/ads/conversations/{$conversationId}/delete")
            ->assertOk()
            ->assertJsonPath('meta.message', 'Conversation hidden successfully.');

        $this->assertDatabaseMissing('ad_conversation_user', [
            'ad_conversation_id' => $conversationId,
            'user_id' => $buyer->id,
            'deleted_at' => null,
        ]);

        $this->getJson('/api/ads/conversations')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        Passport::actingAs($seller);

        $this->getJson('/api/ads/conversations')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationId);

        $this->postJson("/api/ads/conversations/{$conversationId}/messages", [
            'message' => 'Yes, it is available.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.body', 'Yes, it is available.')
            ->assertJsonPath('data.type', 'text')
            ->assertJsonPath('data.sender.id', $seller->id);

        Passport::actingAs($buyer);

        $this->assertDatabaseHas('ad_conversation_user', [
            'ad_conversation_id' => $conversationId,
            'user_id' => $buyer->id,
            'deleted_at' => null,
        ]);

        $this->getJson('/api/ads/conversations')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.latest_message.body', 'Yes, it is available.')
            ->assertJsonPath('data.0.latest_message.type', 'text');

        $this->getJson("/api/ads/conversations/{$conversationId}/messages")
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.body', 'Is this still available?')
            ->assertJsonPath('data.1.body', 'Yes, it is available.');
    }

    public function test_non_participants_cannot_access_conversation(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $intruder = User::factory()->create();

        $ad = Ad::factory()->for($seller, 'user')->create();

        $conversation = AdConversation::create([
            'ad_id' => $ad->id,
            'initiated_by' => $buyer->id,
        ]);

        $conversation->participants()->sync([$seller->id, $buyer->id]);

        Passport::actingAs($intruder);

        $this->getJson('/api/ads/conversations')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson("/api/ads/conversations/{$conversation->id}/messages")
            ->assertNotFound();

        $this->postJson("/api/ads/conversations/{$conversation->id}/messages", [
            'message' => 'Hello there',
        ])->assertForbidden();
    }

    public function test_conversation_index_supports_advanced_filters(): void
    {
        $user = User::factory()->create();
        $participant = User::factory()->create();

        Passport::actingAs($user);

        $adOne = Ad::factory()
            ->for($user, 'user')
            ->create([
                'advertisable_type' => 'Modules\\Ad\\Models\\Example',
                'advertisable_id' => 101,
            ]);

        $adTwo = Ad::factory()
            ->for($user, 'user')
            ->create([
                'advertisable_type' => 'Modules\\Ad\\Models\\AnotherExample',
                'advertisable_id' => 202,
            ]);

        $conversationOne = AdConversation::create([
            'ad_id' => $adOne->id,
            'initiated_by' => $user->id,
        ]);

        $conversationTwo = AdConversation::create([
            'ad_id' => $adTwo->id,
            'initiated_by' => $participant->id,
        ]);

        $conversationOne->participants()->sync([$user->id, $participant->id]);
        $conversationTwo->participants()->sync([$user->id, $participant->id]);

        $conversationOne->messages()->create([
            'user_id' => $user->id,
            'body' => 'First conversation',
            'type' => 'text',
        ]);

        $conversationTwo->messages()->create([
            'user_id' => $participant->id,
            'body' => 'Second conversation',
            'type' => 'text',
        ]);

        $now = Carbon::now();

        AdConversation::query()->whereKey($conversationOne->id)->update([
            'created_at' => $now->copy()->subDays(10),
            'updated_at' => $now->copy()->subDays(7),
        ]);

        AdConversation::query()->whereKey($conversationTwo->id)->update([
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now->copy()->subDay(),
        ]);

        $this->getJson("/api/ads/conversations?ad_id={$adOne->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationOne->id);

        $this->getJson('/api/ads/conversations?advertisable_type=' . urlencode('Modules\\Ad\\Models\\AnotherExample'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationTwo->id);

        $updatedFrom = $now->copy()->subDays(2)->toISOString();
        $updatedTo = $now->toISOString();

        $this->getJson("/api/ads/conversations?updated_from={$updatedFrom}&updated_to={$updatedTo}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationTwo->id);

        $createdTo = $now->copy()->subDays(5)->toISOString();

        $this->getJson("/api/ads/conversations?created_to={$createdTo}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $conversationOne->id);
    }

    public function test_users_can_send_rich_media_and_location_messages(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $ad = Ad::factory()->for($seller, 'user')->create();

        $conversation = AdConversation::create([
            'ad_id' => $ad->id,
            'initiated_by' => $buyer->id,
        ]);

        $conversation->participants()->sync([$seller->id, $buyer->id]);

        Storage::fake('public');

        Passport::actingAs($seller);

        $imageResponse = $this->postJson("/api/ads/conversations/{$conversation->id}/messages", [
            'message_type' => 'image',
            'attachment' => UploadedFile::fake()->image('photo.jpg', 640, 480),
        ]);

        $imageResponse
            ->assertCreated()
            ->assertJsonPath('data.type', 'image')
            ->assertJsonPath('data.body', null)
            ->assertJsonPath('data.payload.disk', 'public')
            ->assertJsonStructure(['data' => ['attachment_url']]);

        $imagePath = $imageResponse->json('data.payload.path');

        $this->assertNotNull($imagePath);
        $this->assertNotNull($imageResponse->json('data.attachment_url'));
        Storage::disk('public')->assertExists($imagePath);

        $this->assertDatabaseHas('ad_messages', [
            'ad_conversation_id' => $conversation->id,
            'type' => 'image',
        ]);

        $locationResponse = $this->postJson("/api/ads/conversations/{$conversation->id}/messages", [
            'message_type' => 'location',
            'location' => ['latitude' => 48.8566, 'longitude' => 2.3522],
        ]);

        $locationResponse
            ->assertCreated()
            ->assertJsonPath('data.type', 'location')
            ->assertJsonPath('data.payload.latitude', 48.8566)
            ->assertJsonPath('data.payload.longitude', 2.3522)
            ->assertJsonPath('data.body', null);

        $this->assertDatabaseHas('ad_messages', [
            'ad_conversation_id' => $conversation->id,
            'type' => 'location',
        ]);
    }

    public function test_blocked_users_cannot_start_or_continue_conversations(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $ad = Ad::factory()->for($seller, 'user')->create();

        $seller->blockedUsers()->syncWithoutDetaching([$buyer->id]);

        Passport::actingAs($buyer);

        $this->postJson("/api/ads/{$ad->id}/conversations", [
            'message' => 'Interested in your ad.',
        ])->assertForbidden();

        $conversation = AdConversation::create([
            'ad_id' => $ad->id,
            'initiated_by' => $buyer->id,
        ]);

        $conversation->participants()->sync([$seller->id, $buyer->id]);

        $this->postJson("/api/ads/conversations/{$conversation->id}/messages", [
            'message' => 'Can we talk?',
        ])->assertForbidden();

        Passport::actingAs($seller);

        $this->postJson("/api/ads/conversations/{$conversation->id}/messages", [
            'message' => 'Please stop messaging.',
        ])->assertForbidden();
    }

    /**
     * Override migration execution to avoid modules with unsupported schema definitions in sqlite.
     */
    protected function migrateDatabases(): void
    {
        $this->artisan('migrate:fresh', array_merge(
            $this->migrateFreshUsing(),
            [
                '--path' => database_path('migrations'),
                '--realpath' => true,
            ],
        ));

        $this->artisan('migrate', [
            '--path' => base_path('Modules/Ad/database/migrations'),
            '--realpath' => true,
        ]);
    }
}
