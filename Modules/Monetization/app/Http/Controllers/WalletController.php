<?php

namespace Modules\Monetization\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Services\TopUpWallet;
use Modules\Monetization\Http\Resources\WalletResource;
use Symfony\Component\HttpFoundation\Response;

class WalletController
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly TopUpWallet $topUpWallet,
    ) {
    }

    public function show(Request $request): JsonResponse
    {
        $wallet = $this->walletRepository->findOrCreateForUser($request->user()->getKey(), 'IRR');

        return (new WalletResource($wallet))
            ->response($request)
            ->setStatusCode(Response::HTTP_OK);
    }

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
