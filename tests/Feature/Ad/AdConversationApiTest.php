<?php

namespace Tests\Feature\Ad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertJsonPath('data.latest_message.body', 'Is this still available?');

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
            ->assertJsonPath('data.0.latest_message.body', 'Yes, it is available.');

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
