<?php

namespace Modules\Monetization\Tests\Feature\Wallet;

use App\Models\User;
use Modules\Monetization\Domain\Entities\Wallet;
use Modules\Monetization\Domain\Entities\WalletTransaction;
use Modules\Monetization\Tests\TestCase;

class WalletEndpointsTest extends TestCase
{
    public function test_authenticated_user_can_fetch_wallet_details(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/monetization/wallet');

        $response->assertOk();
        $response->assertJsonPath('data.currency', 'IRR');
        $response->assertJsonStructure([
            'data' => ['id', 'balance', 'currency'],
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->getKey(),
            'currency' => 'IRR',
        ]);
    }

    public function test_user_can_top_up_wallet_balance(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/monetization/wallet/top-up', [
            'amount' => 1500,
            'currency' => 'IRR',
        ]);

        $response->assertOk();

        $walletId = $response->json('data.id');
        $wallet = Wallet::query()->findOrFail($walletId);

        $this->assertSame(1500.0, $wallet->balance);
        $this->assertSame('IRR', $wallet->currency);

        $this->assertTrue(
            WalletTransaction::query()
                ->where('wallet_id', $walletId)
                ->where('type', WalletTransaction::TYPE_DEPOSIT)
                ->where('amount', 1500.0)
                ->exists()
        );

        $this->assertEquals(1500.0, (float) $response->json('data.balance'));
    }
}
