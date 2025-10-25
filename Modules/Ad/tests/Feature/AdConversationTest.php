<?php

declare(strict_types=1);

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Tests\TestCase;

class AdConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversation_flow_between_buyer_and_seller(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $seller->id]);

        Passport::actingAs($buyer);

        $startResponse = $this->postJson("/api/ads/{$ad->id}/conversations", [
            'message' => 'Hi, is this still available?',
        ]);
        $startResponse->assertCreated();
        $conversationId = $startResponse->json('data.id');
        $this->assertNotNull($conversationId);

        $indexResponse = $this->getJson('/api/conversations');
        $indexResponse->assertOk();
        $indexResponse->assertJsonPath('data.0.id', $conversationId);
        $indexResponse->assertJsonPath('data.0.unread_messages_count', 0);

        $showResponse = $this->getJson("/api/conversations/{$conversationId}");
        $showResponse->assertOk();
        $showResponse->assertJsonPath('data.messages.0.body', 'Hi, is this still available?');

        $messageResponse = $this->postJson("/api/conversations/{$conversationId}/messages", [
            'message' => 'Following up on my request.',
        ]);
        $messageResponse->assertCreated();

        $deleteResponse = $this->deleteJson("/api/conversations/{$conversationId}");
        $deleteResponse->assertOk();

        $this->assertDatabaseHas('ad_conversation_participants', [
            'conversation_id' => $conversationId,
            'user_id' => $buyer->id,
        ]);
    }

    public function test_blocked_users_cannot_start_conversation(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $seller->id]);

        $seller->blockedUsers()->attach($buyer->id, ['blocked_at' => now()]);

        Passport::actingAs($buyer);

        $response = $this->postJson("/api/ads/{$ad->id}/conversations", [
            'message' => 'Attempting to contact.',
        ]);

        $response->assertStatus(403);
    }
}
