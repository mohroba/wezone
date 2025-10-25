<?php

declare(strict_types=1);

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Tests\TestCase;

class AdEngagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_like_comment_and_bookmark_workflow(): void
    {
        $owner = User::factory()->create();
        $visitor = User::factory()->create();
        $ad = Ad::factory()->create(['user_id' => $owner->id, 'view_count' => 0, 'favorite_count' => 0, 'like_count' => 0]);

        $response = $this->postJson("/api/ads/{$ad->id}/views");
        $response->assertCreated();
        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'view_count' => 1]);

        Passport::actingAs($visitor);

        $commentResponse = $this->postJson("/api/ads/{$ad->id}/comments", [
            'body' => 'Interested in this item.',
        ]);
        $commentResponse->assertCreated();
        $commentId = $commentResponse->json('data.id');
        $this->assertNotNull($commentId);
        $this->assertDatabaseHas('ad_comments', ['id' => $commentId, 'ad_id' => $ad->id]);

        $replyResponse = $this->postJson("/api/ads/{$ad->id}/comments/{$commentId}/reply", [
            'body' => 'Can you provide more details?',
        ]);
        $replyResponse->assertCreated();

        $commentsIndex = $this->getJson("/api/ads/{$ad->id}/comments");
        $commentsIndex->assertOk();
        $commentsIndex->assertJsonCount(1, 'data');
        $commentsIndex->assertJsonPath('data.0.replies.0.body', 'Can you provide more details?');

        $likeResponse = $this->postJson("/api/ads/{$ad->id}/like");
        $likeResponse->assertCreated();
        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'like_count' => 1]);

        $bookmarkResponse = $this->postJson("/api/ads/{$ad->id}/bookmark");
        $bookmarkResponse->assertCreated();
        $this->assertDatabaseHas('ads', ['id' => $ad->id, 'favorite_count' => 1]);

        $bookmarksIndex = $this->getJson('/api/bookmarked-ads');
        $bookmarksIndex->assertOk();
        $bookmarksIndex->assertJsonPath('data.0.id', $ad->id);

        $this->deleteJson("/api/ads/{$ad->id}/like")->assertOk();
        $this->deleteJson("/api/ads/{$ad->id}/bookmark")->assertOk();
        $this->deleteJson("/api/ad-comments/{$commentId}")->assertOk();

        $this->assertDatabaseMissing('ad_comments', ['id' => $commentId, 'deleted_at' => null]);
    }
}
