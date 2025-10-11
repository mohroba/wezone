<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdReport;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_authenticated_user_can_submit_report(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson('/api/ad-reports', [
            'ad_id' => $ad->id,
            'reason_code' => 'spam',
            'description' => 'Inappropriate content detected.',
            'metadata' => ['ip' => '127.0.0.1'],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.reason_code', 'spam')
            ->assertJsonPath('data.reported_by', $user->id);

        $this->assertDatabaseHas('ad_reports', [
            'ad_id' => $ad->id,
            'reported_by' => $user->id,
            'reason_code' => 'spam',
        ]);
    }

    public function test_moderator_can_list_reports_with_filters(): void
    {
        $moderator = $this->createModerator();
        $ad = Ad::factory()->create();

        AdReport::factory()->create([
            'ad_id' => $ad->id,
            'status' => 'pending',
            'reason_code' => 'spam',
        ]);

        AdReport::factory()->create([
            'status' => 'resolved',
        ]);

        Passport::actingAs($moderator);

        $response = $this->getJson('/api/ad-reports?status=pending');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'pending');
    }

    public function test_non_moderator_cannot_access_report_listing(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $this->getJson('/api/ad-reports')->assertForbidden();
    }

    public function test_moderator_can_update_report_status(): void
    {
        $moderator = $this->createModerator();
        $report = AdReport::factory()->create([
            'status' => 'pending',
        ]);

        Passport::actingAs($moderator);

        $response = $this->patchJson("/api/ad-reports/{$report->id}", [
            'status' => 'in_review',
            'resolution_notes' => 'Investigating the reported content.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', 'in_review')
            ->assertJsonPath('data.resolution_notes', 'Investigating the reported content.')
            ->assertJsonPath('data.handled_by', $moderator->id);

        $this->assertDatabaseHas('ad_reports', [
            'id' => $report->id,
            'status' => 'in_review',
            'handled_by' => $moderator->id,
        ]);
    }

    public function test_moderator_can_resolve_report(): void
    {
        $moderator = $this->createModerator();
        $report = AdReport::factory()->create([
            'status' => 'in_review',
        ]);

        Passport::actingAs($moderator);

        $response = $this->postJson("/api/ad-reports/{$report->id}/resolve", [
            'resolution_notes' => 'Issue addressed successfully.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', 'resolved')
            ->assertJsonPath('data.handled_by', $moderator->id)
            ->assertJsonPath('data.resolution_notes', 'Issue addressed successfully.');

        $this->assertDatabaseHas('ad_reports', [
            'id' => $report->id,
            'status' => 'resolved',
            'handled_by' => $moderator->id,
            'resolution_notes' => 'Issue addressed successfully.',
        ]);
    }

    public function test_moderator_can_dismiss_report(): void
    {
        $moderator = $this->createModerator();
        $report = AdReport::factory()->create([
            'status' => 'pending',
        ]);

        Passport::actingAs($moderator);

        $response = $this->postJson("/api/ad-reports/{$report->id}/dismiss", [
            'resolution_notes' => 'Report dismissed after review.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', 'dismissed')
            ->assertJsonPath('data.resolution_notes', 'Report dismissed after review.');

        $this->assertDatabaseHas('ad_reports', [
            'id' => $report->id,
            'status' => 'dismissed',
            'resolution_notes' => 'Report dismissed after review.',
        ]);
    }

    private function createModerator(): User
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate([
            'name' => 'ad.report.manage',
            'guard_name' => 'api',
        ]);
        $user->givePermissionTo($permission);

        return $user;
    }
}
