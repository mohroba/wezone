<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdComment;
use Modules\Ad\Tests\Support\RefreshesAdDatabase;
use Tests\TestCase;

class AdCommentControllerTest extends TestCase
{
    use RefreshesAdDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
    }

    public function test_authenticated_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/ads/{$ad->id}/comments", [
            'body' => 'This ad looks promising.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.body', 'This ad looks promising.')
            ->assertJsonPath('data.user.id', $user->id);

        $this->assertDatabaseHas('ad_comments', [
            'ad_id' => $ad->id,
            'user_id' => $user->id,
            'body' => 'This ad looks promising.',
        ]);
    }

    public function test_authenticated_user_can_reply_to_comment(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create();
        $parent = AdComment::factory()->for($ad, 'ad')->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/ads/{$ad->id}/comments", [
            'body' => 'Appreciate the details!',
            'parent_id' => $parent->id,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.parent_id', $parent->id)
            ->assertJsonPath('data.body', 'Appreciate the details!');

        $this->assertDatabaseHas('ad_comments', [
            'ad_id' => $ad->id,
            'parent_id' => $parent->id,
            'body' => 'Appreciate the details!',
        ]);
    }

    public function test_reply_validation_fails_when_parent_is_from_another_ad(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create();
        $otherAd = Ad::factory()->create();
        $foreignParent = AdComment::factory()->for($otherAd, 'ad')->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/ads/{$ad->id}/comments", [
            'body' => 'Trying to reply across ads.',
            'parent_id' => $foreignParent->id,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_can_list_comment_threads_with_replies(): void
    {
        $ad = Ad::factory()->create();
        $author = User::factory()->create();
        $responder = User::factory()->create();

        $rootComment = AdComment::factory()
            ->for($ad, 'ad')
            ->for($author, 'user')
            ->create([
                'body' => 'Is the price negotiable?',
            ]);

        AdComment::factory()
            ->for($ad, 'ad')
            ->for($responder, 'user')
            ->create([
                'parent_id' => $rootComment->id,
                'body' => 'Yes, feel free to make an offer.',
            ]);

        $response = $this->getJson("/api/ads/{$ad->id}/comments");

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.body', 'Is the price negotiable?')
            ->assertJsonPath('data.0.replies.0.body', 'Yes, feel free to make an offer.')
            ->assertJsonPath('data.0.replies.0.user.id', $responder->id);
    }

    public function test_can_list_threaded_comments_with_depth_limit(): void
    {
        $ad = Ad::factory()->create();
        $author = User::factory()->create();
        $replyAuthor = User::factory()->create();

        $rootComment = AdComment::factory()
            ->for($ad, 'ad')
            ->for($author, 'user')
            ->create([
                'body' => 'Root level comment.',
            ]);

        $firstReply = AdComment::factory()
            ->for($ad, 'ad')
            ->for($replyAuthor, 'user')
            ->create([
                'parent_id' => $rootComment->id,
                'body' => 'First reply.',
            ]);

        $secondReply = AdComment::factory()
            ->for($ad, 'ad')
            ->for($replyAuthor, 'user')
            ->create([
                'parent_id' => $firstReply->id,
                'body' => 'Second reply.',
            ]);

        $thirdReply = AdComment::factory()
            ->for($ad, 'ad')
            ->for($replyAuthor, 'user')
            ->create([
                'parent_id' => $secondReply->id,
                'body' => 'Third reply.',
            ]);

        AdComment::factory()
            ->for($ad, 'ad')
            ->for($replyAuthor, 'user')
            ->create([
                'parent_id' => $thirdReply->id,
                'body' => 'Fourth reply should be truncated.',
            ]);

        $response = $this->getJson("/api/ads/{$ad->id}/comments/threaded");

        $response
            ->assertOk()
            ->assertJsonPath('data.0.replies.0.body', 'First reply.')
            ->assertJsonPath('data.0.replies.0.replies.0.body', 'Second reply.')
            ->assertJsonPath('data.0.replies.0.replies.0.replies.0.body', 'Third reply.')
            ->assertJsonMissing(['body' => 'Fourth reply should be truncated.']);
    }
}
