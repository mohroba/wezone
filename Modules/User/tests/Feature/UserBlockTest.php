<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserBlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_block_and_unblock_users(): void
    {
        $authUser = User::factory()->create();
        $target = User::factory()->create();

        Passport::actingAs($authUser);

        $blockResponse = $this->postJson("/api/users/{$target->id}/block");
        $blockResponse->assertStatus(201);
        $this->assertDatabaseHas('user_blocks', [
            'blocker_id' => $authUser->id,
            'blocked_id' => $target->id,
        ]);

        $listResponse = $this->getJson('/api/users/blocked');
        $listResponse->assertOk();
        $listResponse->assertJsonPath('data.0.id', $target->id);

        $unblockResponse = $this->deleteJson("/api/users/{$target->id}/block");
        $unblockResponse->assertOk();
        $this->assertDatabaseMissing('user_blocks', [
            'blocker_id' => $authUser->id,
            'blocked_id' => $target->id,
        ]);
    }
}
