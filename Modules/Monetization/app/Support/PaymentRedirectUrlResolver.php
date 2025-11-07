<?php

namespace Modules\Monetization\Support;

use Illuminate\Support\Facades\Config;
use Modules\Monetization\Domain\Entities\Payment;

class PaymentRedirectUrlResolver
{
    public function resolve(Payment $payment): ?string
    {
        $config = Config::get('monetization.gateways.' . $payment->gateway, []);

        if (! is_array($config)) {
            return null;
        }

        return match ($payment->gateway) {
            'stripe' => $this->resolveStripeUrl($payment, $config),
            'idpay' => $this->formatWithPattern(
                $payment->ref_id,
                ($config['sandbox'] ?? false)
                    ? ($config['sandbox_redirect_url'] ?? null)
                    : ($config['redirect_url'] ?? null)
            ),
            default => $this->formatWithPattern($payment->ref_id, $config['redirect_url'] ?? null),
        };
    }

    private function resolveStripeUrl(Payment $payment, array $config): ?string
    {
        $key = $config['redirect_url_key'] ?? 'url';
        $url = data_get($payment->request_payload, $key) ?? data_get($payment->response_payload, $key);

        return is_string($url) && $url !== '' ? $url : null;
    }

    private function formatWithPattern(?string $reference, ?string $pattern): ?string
    {
        if (! is_string($pattern) || $pattern === '') {
            return null;
        }

        if (! is_string($reference) || $reference === '') {
            return null;
        }

        return sprintf($pattern, $reference);
    }
}
