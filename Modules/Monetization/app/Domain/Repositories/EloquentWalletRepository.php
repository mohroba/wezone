<?php

namespace Modules\Monetization\Domain\Repositories;

use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Entities\Wallet;
use Modules\Monetization\Domain\Entities\WalletTransaction;
use Illuminate\Support\Facades\DB;

class EloquentWalletRepository implements WalletRepository
{
    public function findOrCreateForUser(int $userId, string $currency): Wallet
    {
        return Wallet::query()->firstOrCreate(
            ['user_id' => $userId],
            ['currency' => $currency, 'balance' => 0]
        );
    }

    public function createTransaction(Wallet $wallet, array $attributes): WalletTransaction
    {
        return $wallet->transactions()->create($attributes);
    }

    public function updateBalance(Wallet $wallet, float $amount): Wallet
    {
        return DB::transaction(function () use ($wallet, $amount): Wallet {
            $wallet->refresh();
            $wallet->balance = round($wallet->balance + $amount, 2);
            $wallet->save();

            return $wallet;
        });
    }
}
