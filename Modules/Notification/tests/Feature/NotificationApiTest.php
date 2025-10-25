<?php

namespace Modules\Notification\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase {
        migrateFreshUsing as baseMigrateFreshUsing;
    }

    private static bool $routesRegistered = false;
    private static bool $envStubCreated = false;
    private static ?string $envStubPath = null;

    protected function setUp(): void
    {
        if (! self::$envStubCreated) {
            $envPath = dirname(__DIR__, 4).'/.env';

            if (! file_exists($envPath)) {
                file_put_contents($envPath, "APP_KEY=base64:\n");
                self::$envStubCreated = true;
                self::$envStubPath = $envPath;
            }
        }

        parent::setUp();

        if (! self::$routesRegistered) {
            Route::middleware('api')->prefix('api')->name('api.')->group(function (): void {
                require module_path('Notification', '/routes/api.php');
            });

            self::$routesRegistered = true;
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (self::$envStubCreated && self::$envStubPath !== null && file_exists(self::$envStubPath)) {
            @unlink(self::$envStubPath);
            self::$envStubCreated = false;
            self::$envStubPath = null;
        }
    }

    /**
     * Limit migrations to the application's core paths to avoid module conflicts during testing.
     */
    protected function migrateFreshUsing(): array
    {
        return array_merge($this->baseMigrateFreshUsing(), [
            '--path' => 'database/migrations',
        ]);
    }

    public function test_user_can_list_unread_notifications(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $visible = $this->createNotification($user, ['created_at' => now()->subMinutes(1)]);
        $this->createNotification($user, ['read_at' => now()->subDay(), 'created_at' => now()->subDays(2)]);
        $this->createNotification($otherUser);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/notifications');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $visible->id);
        $response->assertJsonPath('meta.total', 1);
    }

    public function test_user_can_include_read_notifications(): void
    {
        $user = User::factory()->create();

        $unread = $this->createNotification($user, ['created_at' => now()->subMinutes(2)]);
        $read = $this->createNotification($user, ['read_at' => now()->subMinute(), 'created_at' => now()->subMinute()]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/v1/notifications?include_read=1');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.id', $read->id);
        $response->assertJsonPath('data.1.id', $unread->id);
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $notification = $this->createNotification($user);

        Passport::actingAs($user);

        $response = $this->postJson("/api/v1/notifications/{$notification->id}/read");

        $response->assertOk();
        $response->assertJsonPath('id', $notification->id);
        $response->assertJsonStructure(['read_at']);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_acknowledge_notification(): void
    {
        $user = User::factory()->create();
        $notification = $this->createNotification($user);

        Passport::actingAs($user);

        $response = $this->postJson("/api/v1/notifications/{$notification->id}/acknowledge");

        $response->assertOk();
        $response->assertJsonPath('id', $notification->id);
        $response->assertJsonStructure(['acknowledged_at']);

        $this->assertNotNull($notification->fresh()->acknowledged_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $this->createNotification($user);
        $this->createNotification($user);

        Passport::actingAs($user);

        $response = $this->postJson('/api/v1/notifications/read');

        $response->assertOk();
        $response->assertJsonPath('updated', 2);

        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    private function createNotification(User $user, array $attributes = []): DatabaseNotification
    {
        $defaults = [
            'id' => (string) Str::uuid(),
            'type' => 'test',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->getKey(),
            'data' => ['message' => 'Test notification'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return DatabaseNotification::query()->create(array_merge($defaults, $attributes));
    }
}
