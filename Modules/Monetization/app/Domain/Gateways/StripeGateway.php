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

class StripeGateway extends AbstractGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'stripe';
    }

    public function initiate(GatewayInitiationData $data): Payment
    {
        $response = Http::withBasicAuth($this->config('secret_key'), '')
            ->asForm()
            ->post('https://api.stripe.com/v1/payment_links', [
                'line_items[0][price_data][currency]' => strtolower($data->money->currency()->code()),
                'line_items[0][price_data][product_data][name]' => $data->plan->name,
                'line_items[0][price_data][unit_amount]' => (int) ($data->money->amount() * 100),
                'line_items[0][quantity]' => 1,
                'after_completion[type]' => 'redirect',
                'after_completion[redirect][url]' => $data->callbackUrl,
            ]);

        if ($response->failed()) {
            Log::error('stripe.initiate_failed', $response->json() ?? []);
            throw new RuntimeException('Stripe initiate failed.');
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
        $paymentIntent = $data->payload['data']['object']['payment_intent'] ?? null;
        if (! $paymentIntent) {
            throw new RuntimeException('Invalid Stripe webhook payload.');
        }

        $data->payment->fill([
            'status' => 'paid',
            'tracking_code' => $paymentIntent,
            'response_payload' => $data->payload,
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
