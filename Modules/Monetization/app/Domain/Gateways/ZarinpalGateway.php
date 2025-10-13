<?php

namespace Modules\Monetization\Domain\Gateways;

use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\Entities\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ZarinpalGateway extends AbstractGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'zarinpal';
    }

    public function initiate(GatewayInitiationData $data): Payment
    {
        $response = Http::asJson()->post('https://api.zarinpal.com/pg/v4/payment/request.json', [
            'merchant_id' => $this->config('merchant_id'),
            'amount' => (int) $data->money->amount(),
            'callback_url' => $data->callbackUrl,
            'description' => 'WeZone plan purchase',
        ]);

        if ($response->failed()) {
            Log::error('zarinpal.initiate_failed', $response->json() ?? []);
            throw new RuntimeException('Zarinpal initiate failed.');
        }

        /** @var Payment $payment */
        $payment = $data->purchase->payments()->latest()->firstOrFail();
        $payment->update([
            'ref_id' => $response->json('data.authority'),
            'request_payload' => $response->json(),
        ]);

        return $payment;
    }

    public function verify(GatewayVerificationData $data): Payment
    {
        $response = Http::asJson()->post('https://api.zarinpal.com/pg/v4/payment/verify.json', [
            'merchant_id' => $this->config('merchant_id'),
            'authority' => $data->payload['authority'] ?? null,
            'amount' => (int) $data->payment->amount,
        ]);

        if ($response->failed()) {
            Log::error('zarinpal.verify_failed', $response->json() ?? []);
            throw new RuntimeException('Zarinpal verification failed.');
        }

        $data->payment->fill([
            'status' => 'paid',
            'tracking_code' => $response->json('data.ref_id'),
            'response_payload' => $response->json(),
            'paid_at' => now(),
        ])->save();

        return $data->payment;
    }

    public function refund(GatewayRefundData $data): Payment
    {
        $data->payment->fill([
            'status' => 'refunded',
            'refunded_at' => now(),
        ])->save();

        return $data->payment;
    }
}
