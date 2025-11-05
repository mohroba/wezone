<?php

namespace Modules\Monetization\Domain\Gateways;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\Entities\Payment;
use RuntimeException;

class PayPingGateway extends AbstractGateway implements PaymentGatewayInterface
{
    private const BASE_URI = 'https://api.payping.ir';
    private const SANDBOX_URI = 'https://sandbox-api.payping.ir';

    public function getName(): string
    {
        return 'payping';
    }

    public function initiate(GatewayInitiationData $data): Payment
    {
        $this->assertConfigured();

        $payload = $this->filterPayload([
            'amount' => $this->normalizeAmount($data->money->amount()),
            'clientRefId' => (string) $data->purchase->getKey(),
            'returnUrl' => $this->resolveCallbackUrl($data->callbackUrl),
            'description' => sprintf('Plan purchase #%d', $data->purchase->getKey()),
        ]);

        $response = $this->httpClient()->post($this->endpoint('/v2/payments'), $payload);

        if ($response->failed()) {
            Log::error('payping.initiate_failed', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            throw new RuntimeException('PayPing initiate failed.');
        }

        $reference = $response->json('code');
        if (! is_string($reference) || $reference === '') {
            Log::error('payping.initiate_invalid_response', [
                'response' => $response->json(),
            ]);

            throw new RuntimeException('PayPing initiate response missing reference code.');
        }

        /** @var Payment $payment */
        $payment = $data->purchase->payments()->latest()->firstOrFail();
        $payment->update([
            'ref_id' => $reference,
            'request_payload' => $payload,
            'response_payload' => $response->json(),
        ]);

        return $payment;
    }

    public function verify(GatewayVerificationData $data): Payment
    {
        $this->assertConfigured();

        $payload = $this->filterPayload([
            'refId' => $data->payment->ref_id,
            'amount' => $this->normalizeAmount($data->payment->amount),
        ]);

        $response = $this->httpClient()->post($this->endpoint('/v2/payments/verify'), $payload);

        if ($response->failed()) {
            Log::error('payping.verify_failed', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            throw new RuntimeException('PayPing verification failed.');
        }

        $trackingCode = $response->json('trackingCode')
            ?? $response->json('tracking_code')
            ?? $response->json('trackId');

        $data->payment->fill($this->filterPayload([
            'status' => 'paid',
            'tracking_code' => $trackingCode ? (string) $trackingCode : $data->payment->tracking_code,
            'response_payload' => $response->json(),
            'paid_at' => now(),
        ]))->save();

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

    private function httpClient(): PendingRequest
    {
        $request = Http::withToken($this->config('api_token'))
            ->acceptJson()
            ->asJson();

        $timeout = $this->config('timeout');
        if (is_numeric($timeout) && $timeout > 0) {
            $request = $request->timeout((int) $timeout);
        }

        return $request;
    }

    private function endpoint(string $path): string
    {
        $base = $this->config('sandbox', false) ? self::SANDBOX_URI : self::BASE_URI;

        return rtrim($base, '/').$path;
    }

    private function resolveCallbackUrl(?string $provided): string
    {
        $callback = $provided ?: $this->config('callback_url');
        if (! is_string($callback) || $callback === '') {
            throw new RuntimeException('PayPing callback URL is not configured.');
        }

        return $callback;
    }

    private function assertConfigured(): void
    {
        if (! is_string($this->config('api_token')) || $this->config('api_token') === '') {
            throw new RuntimeException('PayPing API token is not configured.');
        }
    }

    private function normalizeAmount(float $amount): int
    {
        return (int) max(0, round($amount));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function filterPayload(array $payload): array
    {
        return array_filter($payload, static fn ($value) => $value !== null && $value !== '');
    }
}
