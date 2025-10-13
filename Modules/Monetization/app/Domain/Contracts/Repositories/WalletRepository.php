<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Modules\Monetization\Domain\Entities\Wallet;
use Modules\Monetization\Domain\Entities\WalletTransaction;

interface WalletRepository
{
    public function findOrCreateForUser(int $userId, string $currency): Wallet;

    public function createTransaction(Wallet $wallet, array $attributes): WalletTransaction;

    public function updateBalance(Wallet $wallet, float $amount): Wallet;
}
