<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Entities\Wallet;
use Modules\Monetization\Domain\Entities\WalletTransaction;
use Illuminate\Support\Facades\DB;

class TopUpWallet
{
    public function __construct(private readonly WalletRepository $walletRepository)
    {
    }

    public function __invoke(int $userId, float $amount, string $currency): Wallet
    {
        return DB::transaction(function () use ($userId, $amount, $currency): Wallet {
            $wallet = $this->walletRepository->findOrCreateForUser($userId, $currency);
            $before = $wallet->balance;
            $this->walletRepository->updateBalance($wallet, $amount);
            $after = $wallet->balance;

            $this->walletRepository->createTransaction($wallet, [
                'type' => WalletTransaction::TYPE_DEPOSIT,
                'amount' => $amount,
                'before_balance' => $before,
                'after_balance' => $after,
                'description' => 'Wallet top up',
            ]);

            return $wallet;
        });
    }
}
