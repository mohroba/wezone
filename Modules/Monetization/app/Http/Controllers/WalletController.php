<?php

namespace Modules\Monetization\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Services\TopUpWallet;
use Modules\Monetization\Http\Resources\WalletResource;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly TopUpWallet $topUpWallet,
    ) {
    }

    /**
     * @group Monetization
     * @authenticated
     *
     * Show wallet balance
     */
    public function show(Request $request): WalletResource
    {
        $wallet = $this->walletRepository->findOrCreateForUser($request->user()->getKey(), 'IRR');

        return new WalletResource($wallet);
    }

    /**
     * @group Monetization
     * @authenticated
     *
     * Top up wallet balance
     */
    public function topUp(Request $request): WalletResource
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $wallet = ($this->topUpWallet)($request->user()->getKey(), (float) $validated['amount'], $validated['currency']);

        return new WalletResource($wallet);
    }
}
