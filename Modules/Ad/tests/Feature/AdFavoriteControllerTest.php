<?php

namespace Modules\Ad\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Nwidart\Modules\Facades\Module;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdFavorite;
use Tests\TestCase;

class AdFavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    private static ?string $moduleStatusBackup = null;

    protected function setUp(): void
    {
        $statusesPath = dirname(__DIR__, 4) . '/modules_statuses.json';

        if (self::$moduleStatusBackup === null && file_exists($statusesPath)) {
            self::$moduleStatusBackup = file_get_contents($statusesPath);
        }

        if (self::$moduleStatusBackup !== null) {
            $statuses = json_decode(self::$moduleStatusBackup, true, 512, JSON_THROW_ON_ERROR);

            if (($statuses['Monetization'] ?? false) === true) {
                $statuses['Monetization'] = false;

                file_put_contents(
                    $statusesPath,
                    json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
                );
            }
        }

        parent::setUp();

        Module::disable('Monetization');
    }

    protected function tearDown(): void
    {
        $statusesPath = dirname(__DIR__, 4) . '/modules_statuses.json';

        if (self::$moduleStatusBackup !== null) {
            file_put_contents($statusesPath, self::$moduleStatusBackup);
        }

        Module::enable('Monetization');

        parent::tearDown();
    }

    public function test_authenticated_user_can_toggle_bookmark_and_updates_favorite_count(): void
    {
        $user = User::factory()->create();
        $ad = Ad::factory()->create(['favorite_count' => 0]);

        Passport::actingAs($user);

        $favoriteRoute = "/api/ads/{$ad->id}/bookmark";

        $firstResponse = $this->postJson($favoriteRoute);
        $firstResponse->assertOk();
        $firstResponse->assertJsonPath('data.favorited', true);
        $firstResponse->assertJsonPath('data.favorite_count', 1);

        $this->assertDatabaseHas('ad_favorites', [
            'ad_id' => $ad->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('ads', [
            'id' => $ad->id,
            'favorite_count' => 1,
        ]);

        $secondResponse = $this->postJson($favoriteRoute);
        $secondResponse->assertOk();
        $secondResponse->assertJsonPath('data.favorited', false);
        $secondResponse->assertJsonPath('data.favorite_count', 0);

        $this->assertDatabaseMissing('ad_favorites', [
            'ad_id' => $ad->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('ads', [
            'id' => $ad->id,
            'favorite_count' => 0,
        ]);
    }

    public function test_authenticated_user_can_list_bookmarks(): void
    {
        $user = User::factory()->create();
        $ads = Ad::factory()->count(2)->create(['favorite_count' => 0]);

        Passport::actingAs($user);

        $ads->each(function (Ad $ad) use ($user): void {
            AdFavorite::create([
                'ad_id' => $ad->id,
                'user_id' => $user->id,
            ]);

            $ad->update(['favorite_count' => 1]);
        });

        $response = $this->getJson('/api/ads/bookmarks');
        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $returnedFavoriteAdIds = collect($response->json('data'))
            ->pluck('ad_id')
            ->all();

        $this->assertEqualsCanonicalizing($ads->pluck('id')->all(), $returnedFavoriteAdIds);

        $response->assertJsonPath('data.0.ad.id', $response->json('data.0.ad_id'));
    }
}
