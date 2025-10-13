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

class IDPayGateway extends AbstractGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'idpay';
    }

    public function initiate(GatewayInitiationData $data): Payment
    {
        $response = Http::withHeaders([
            'X-API-KEY' => $this->config('api_key'),
            'X-SANDBOX' => $this->config('sandbox', true) ? '1' : '0',
        ])->post('https://api.idpay.ir/v1.1/payment', [
            'order_id' => $data->purchase->getKey(),
            'amount' => (int) $data->money->amount(),
            'callback' => $data->callbackUrl,
        ]);

        if ($response->failed()) {
            Log::error('idpay.initiate_failed', $response->json() ?? []);
            throw new RuntimeException('IDPay initiate failed.');
        }

        /** @var Payment $payment */
        $payment = $data->purchase->payments()->latest()->firstOrFail();
        $payment->update([
            'ref_id' => $response->json('id'),
            'request_payload' => $response->json(),
        ]);

        return $payment;
    }

    public function verify(GatewayVerificationData $data): Payment
    {
        $response = Http::withHeaders([
            'X-API-KEY' => $this->config('api_key'),
            'X-SANDBOX' => $this->config('sandbox', true) ? '1' : '0',
        ])->post('https://api.idpay.ir/v1.1/payment/verify', [
            'id' => $data->payment->ref_id,
            'order_id' => $data->payment->payable_id,
        ]);

        if ($response->failed()) {
            Log::error('idpay.verify_failed', $response->json() ?? []);
            throw new RuntimeException('IDPay verification failed.');
        }

        $data->payment->fill([
            'status' => 'paid',
            'tracking_code' => $response->json('payment.tracking_code'),
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
