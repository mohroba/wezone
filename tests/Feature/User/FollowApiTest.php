<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Auth\Models\Profile;
use Modules\User\Models\UserFollow;
use Tests\TestCase;

class FollowApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_another_user(): void
    {
        [$user, $target] = User::factory()->count(2)->create();
        Profile::factory()->for($user)->create();
        Profile::factory()->for($target)->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$target->id}/follow");

        $response
            ->assertStatus(201)
            ->assertJsonPath('meta.message', 'Followed successfully.')
            ->assertJsonPath('data.id', $target->id);

        $this->assertDatabaseHas('user_follows', [
            'follower_id' => $user->id,
            'followed_id' => $target->id,
        ]);
    }

    public function test_follow_request_is_idempotent(): void
    {
        [$user, $target] = User::factory()->count(2)->create();
        Passport::actingAs($user);

        UserFollow::create([
            'follower_id' => $user->id,
            'followed_id' => $target->id,
        ]);

        $response = $this->postJson("/api/users/{$target->id}/follow");

        $response
            ->assertOk()
            ->assertJsonPath('meta.message', 'Already following user.');
    }

    public function test_user_can_unfollow(): void
    {
        [$user, $target] = User::factory()->count(2)->create();
        Passport::actingAs($user);

        UserFollow::create([
            'follower_id' => $user->id,
            'followed_id' => $target->id,
        ]);

        $response = $this->deleteJson("/api/users/{$target->id}/follow");

        $response
            ->assertOk()
            ->assertJsonPath('meta.message', 'Unfollowed successfully.');

        $this->assertDatabaseMissing('user_follows', [
            'follower_id' => $user->id,
            'followed_id' => $target->id,
        ]);
    }

    public function test_followers_list_can_be_filtered_by_date(): void
    {
        [$user, $followerOne, $followerTwo] = User::factory()->count(3)->create();
        Passport::actingAs($user);

        $olderFollow = UserFollow::create([
            'follower_id' => $followerOne->id,
            'followed_id' => $user->id,
        ]);

        UserFollow::query()->whereKey($olderFollow->id)->update([
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        $recentFollow = UserFollow::create([
            'follower_id' => $followerTwo->id,
            'followed_id' => $user->id,
        ]);

        UserFollow::query()->whereKey($recentFollow->id)->update([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $response = $this->getJson("/api/users/{$user->id}/followers?followed_from=" . now()->subDays(2)->toDateString());

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $followerTwo->id);

        $response = $this->getJson("/api/users/{$user->id}/followers?followed_to=" . now()->subDays(2)->toDateString());

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $followerOne->id);
    }

    public function test_user_index_supports_follower_and_profile_filters(): void
    {
        [$authUser, $followedUser, $otherUser] = User::factory()->count(3)->create();
        Passport::actingAs($authUser);

        Profile::factory()->for($followedUser)->create([
            'first_name' => 'Sara',
            'last_name' => 'Connor',
        ]);

        Profile::factory()->for($otherUser)->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        UserFollow::create([
            'follower_id' => $authUser->id,
            'followed_id' => $followedUser->id,
        ]);

        $response = $this->getJson('/api/users?follower_id=' . $authUser->id . '&username=' . $followedUser->username);

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $followedUser->id)
            ->assertJsonPath('data.0.profile.full_name', 'Sara Connor');
    }
}
