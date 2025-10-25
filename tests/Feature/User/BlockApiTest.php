<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Modules\Auth\Models\Profile;
use Modules\User\Models\UserBlock;
use Modules\User\Models\UserFollow;
use Tests\TestCase;

class BlockApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_block_another_user(): void
    {
        [$blocker, $blocked] = User::factory()->count(2)->create();
        Profile::factory()->for($blocked)->create();

        UserFollow::create([
            'follower_id' => $blocker->id,
            'followed_id' => $blocked->id,
        ]);

        UserFollow::create([
            'follower_id' => $blocked->id,
            'followed_id' => $blocker->id,
        ]);

        Passport::actingAs($blocker);

        $response = $this->postJson("/api/users/{$blocked->id}/block");

        $response
            ->assertStatus(201)
            ->assertJsonPath('meta.message', 'User blocked successfully.')
            ->assertJsonPath('data.id', $blocked->id);

        $this->assertDatabaseHas('user_blocks', [
            'blocker_id' => $blocker->id,
            'blocked_id' => $blocked->id,
        ]);

        $this->assertDatabaseMissing('user_follows', [
            'follower_id' => $blocker->id,
            'followed_id' => $blocked->id,
        ]);

        $this->assertDatabaseMissing('user_follows', [
            'follower_id' => $blocked->id,
            'followed_id' => $blocker->id,
        ]);
    }

    public function test_block_request_is_idempotent(): void
    {
        [$blocker, $blocked] = User::factory()->count(2)->create();
        UserBlock::create([
            'blocker_id' => $blocker->id,
            'blocked_id' => $blocked->id,
        ]);

        Passport::actingAs($blocker);

        $response = $this->postJson("/api/users/{$blocked->id}/block");

        $response
            ->assertOk()
            ->assertJsonPath('meta.message', 'User already blocked.');
    }

    public function test_blocker_cannot_follow_blocked_user(): void
    {
        [$blocker, $blocked] = User::factory()->count(2)->create();
        Passport::actingAs($blocker);

        $this->postJson("/api/users/{$blocked->id}/block");

        $response = $this->postJson("/api/users/{$blocked->id}/follow");

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('errors.user')
                ->where('errors.user.0', 'You cannot follow a user you have blocked.')
                ->etc()
            );
    }

    public function test_blocked_user_cannot_follow_blocker(): void
    {
        [$blocker, $blocked] = User::factory()->count(2)->create();

        Passport::actingAs($blocker);
        $this->postJson("/api/users/{$blocked->id}/block");

        Passport::actingAs($blocked);
        $response = $this->postJson("/api/users/{$blocker->id}/follow");

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json): AssertableJson => $json
                ->has('errors.user')
                ->where('errors.user.0', 'You cannot follow a user who has blocked you.')
                ->etc()
            );
    }

    public function test_user_can_unblock(): void
    {
        [$blocker, $blocked] = User::factory()->count(2)->create();
        UserBlock::create([
            'blocker_id' => $blocker->id,
            'blocked_id' => $blocked->id,
        ]);

        Passport::actingAs($blocker);

        $response = $this->postJson("/api/users/{$blocked->id}/unblock");

        $response
            ->assertOk()
            ->assertJsonPath('meta.message', 'User unblocked successfully.');

        $this->assertDatabaseMissing('user_blocks', [
            'blocker_id' => $blocker->id,
            'blocked_id' => $blocked->id,
        ]);
    }

    public function test_blocked_users_list_returns_blocked_profiles(): void
    {
        $blocker = User::factory()->create();
        $blockedUsers = User::factory()->count(3)->create();

        Passport::actingAs($blocker);

        foreach ($blockedUsers as $user) {
            Profile::factory()->for($user)->create();
            $this->postJson("/api/users/{$user->id}/block");
        }

        $response = $this->getJson('/api/users/blocks?per_page=2');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'profile' => ['full_name']],
                ],
                'links',
                'meta',
            ]);
    }
}
