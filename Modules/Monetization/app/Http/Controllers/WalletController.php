<?php

namespace Modules\Monetization\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Services\TopUpWallet;
use Modules\Monetization\Http\Resources\WalletResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Monetization
 *
 * View and manage the authenticated user's wallet balance.
 */
class WalletController
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly TopUpWallet $topUpWallet,
    ) {
    }

    /**
     * Show wallet balance
     *
     * @group Monetization
     *
     * Retrieve the current wallet balance for the authenticated user. A wallet will be created automatically when missing.
     *
     * @responseField data.id integer Wallet identifier.
     * @responseField data.balance number Current available balance.
     * @responseField data.currency string ISO currency code of the wallet.
     */
    public function show(Request $request): JsonResponse
    {
        $wallet = $this->walletRepository->findOrCreateForUser($request->user()->getKey(), 'IRR');

        return (new WalletResource($wallet))
            ->response($request)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Top up wallet
     *
     * @group Monetization
     *
     * Increase the wallet balance using an offline credit. Amount is applied immediately.
     *
     * @bodyParam amount number required Amount to deposit. Example: 50000
     * @bodyParam currency string required ISO 4217 currency code. Example: IRR
     */
    public function topUp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $wallet = ($this->topUpWallet)($request->user()->getKey(), (float) $validated['amount'], $validated['currency']);

        return (new WalletResource($wallet))
            ->response($request)
            ->setStatusCode(Response::HTTP_OK);
    }
}
