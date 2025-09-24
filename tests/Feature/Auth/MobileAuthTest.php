<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Modules\Auth\Jobs\SendSmsMessage;
use Modules\Auth\Models\Otp;
use Modules\Auth\Models\Profile;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_otp(): void
    {
        Queue::fake();

        $mobile = '09123456789';

        $response = $this->postJson('/api/auth/otp/send', [
            'mobile' => $mobile,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.expires_in', 120);

        $this->assertDatabaseHas('otps', [
            'mobile' => $mobile,
        ]);

        Queue::assertPushed(SendSmsMessage::class);
    }

    public function test_user_cannot_request_otp_twice_within_cooldown(): void
    {
        Queue::fake();

        $mobile = '09123456789';

        $this->postJson('/api/auth/otp/send', ['mobile' => $mobile])->assertCreated();

        $response = $this->postJson('/api/auth/otp/send', ['mobile' => $mobile]);

        $response
            ->assertStatus(429)
            ->assertJsonPath('success', false);

        $this->assertArrayHasKey('retry_after', $response->json('errors'));
    }

    public function test_user_can_verify_otp_and_receive_profile(): void
    {
        $mobile = '09123456789';
        $otpCode = '123456';

        $otp = Otp::create([
            'mobile' => $mobile,
            'code' => Hash::make($otpCode),
            'expires_at' => now()->addMinutes(2),
        ]);

        app(ClientRepository::class)->createPersonalAccessGrantClient('Test Personal Access Client');

        $response = $this->postJson('/api/auth/otp/verify', [
            'mobile' => $mobile,
            'otp' => $otpCode,
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birth_date' => '1990-01-01',
            'residence_city_id' => 10,
            'residence_province_id' => 20,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonPath('data.profile.first_name', 'John')
            ->assertJsonPath('data.profile.full_name', 'John Doe')
            ->assertJsonPath('data.profile.birth_date', '1990-01-01')
            ->assertJsonPath('data.profile.user.mobile', $mobile)
            ->assertJsonPath('data.profile.user.email', 'john@example.com');

        $issuedTokens = $response->json('data');

        $this->assertIsString($issuedTokens['access_token']);
        $this->assertNotSame('', $issuedTokens['access_token']);
        $this->assertIsString($issuedTokens['refresh_token']);
        $this->assertNotSame('', $issuedTokens['refresh_token']);
        $this->assertIsInt($issuedTokens['expires_in']);

        $this->assertNotNull($otp->fresh()->used_at);

        $this->assertDatabaseHas('users', [
            'mobile' => $mobile,
            'username' => 'john_doe',
            'email' => 'john@example.com',
        ]);

        $profileRecord = Profile::where('user_id', User::where('mobile', $mobile)->value('id'))->first();
        $this->assertNotNull($profileRecord);
        $this->assertSame(10, $profileRecord->residence_city_id);
        $this->assertSame(20, $profileRecord->residence_province_id);
        $this->assertSame('1990-01-01', optional($profileRecord->birth_date)->toDateString());
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->for($user)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birth_date' => '1992-02-02',
        ]);

        $role = Role::create(['name' => 'member', 'guard_name' => 'api']);
        $permission = Permission::create(['name' => 'view-profile', 'guard_name' => 'api']);

        $user->assignRole($role);
        $user->givePermissionTo($permission);

        Passport::actingAs($user);

        $response = $this->getJson('/api/auth/profile');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.profile.id', $profile->id)
            ->assertJsonPath('data.profile.full_name', 'Jane Doe')
            ->assertJsonPath('data.profile.birth_date', '1992-02-02')
            ->assertJsonPath('data.profile.user.roles.0', 'member')
            ->assertJsonPath('data.profile.user.permissions.0', 'view-profile');
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/profile', [
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
            'birth_date' => '1995-05-05',
            'residence_city_id' => 55,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.profile.full_name', 'Alice Wonderland')
            ->assertJsonPath('data.profile.birth_date', '1995-05-05')
            ->assertJsonPath('data.profile.residence_city_id', 55);

        $updatedProfile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($updatedProfile);
        $this->assertSame('Alice', $updatedProfile->first_name);
        $this->assertSame('Wonderland', $updatedProfile->last_name);
        $this->assertSame('1995-05-05', optional($updatedProfile->birth_date)->toDateString());
    }

    public function test_authenticated_user_can_update_account_details(): void
    {
        $user = User::factory()->create([
            'username' => 'original_username',
            'email' => null,
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/auth/user', [
            'username' => 'updated_username',
            'email' => 'updated@example.com',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.username', 'updated_username')
            ->assertJsonPath('data.user.email', 'updated@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'updated_username',
            'email' => 'updated@example.com',
        ]);
    }
}
