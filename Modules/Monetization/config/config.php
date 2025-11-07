<?php

use Modules\Monetization\Domain\Gateways\IDPayGateway;
use Modules\Monetization\Domain\Gateways\PayPingGateway;
use Modules\Monetization\Domain\Gateways\StripeGateway;
use Modules\Monetization\Domain\Gateways\ZarinpalGateway;

return [
    'name' => 'Monetization',

    'default_gateway' => env('MONETIZATION_DEFAULT_GATEWAY', 'payping'),

    'gateways' => [
        'payping' => [
            'driver' => PayPingGateway::class,
            'api_token' => env('PAYPING_API_TOKEN'),
            'callback_url' => env('PAYPING_CALLBACK_URL', env('APP_URL').'/api/monetization/payments/verify'),
            'sandbox' => (bool) env('PAYPING_SANDBOX', false),
            'timeout' => (int) env('PAYPING_TIMEOUT', 10),
            'redirect_url' => env('PAYPING_REDIRECT_URL', 'https://next.payping.ir/ipg/?code=%s'),
        ],

        'idpay' => [
            'driver' => IDPayGateway::class,
            'api_key' => env('IDPAY_API_KEY'),
            'callback_url' => env('IDPAY_CALLBACK_URL', env('APP_URL').'/api/monetization/payments/verify'),
            'sandbox' => (bool) env('IDPAY_SANDBOX', true),
            'redirect_url' => env('IDPAY_REDIRECT_URL', 'https://idpay.ir/p/ws/%s'),
            'sandbox_redirect_url' => env('IDPAY_SANDBOX_REDIRECT_URL', 'https://idpay.ir/p/ws-sandbox/%s'),
        ],

        'zarinpal' => [
            'driver' => ZarinpalGateway::class,
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
            'callback_url' => env('ZARINPAL_CALLBACK_URL', env('APP_URL').'/api/monetization/payments/verify'),
            'redirect_url' => env('ZARINPAL_REDIRECT_URL', 'https://www.zarinpal.com/pg/StartPay/%s'),
        ],

        'stripe' => [
            'driver' => StripeGateway::class,
            'secret_key' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'callback_url' => env('STRIPE_CALLBACK_URL', env('APP_URL').'/api/monetization/payments/verify'),
            'redirect_url_key' => env('STRIPE_REDIRECT_URL_KEY', 'url'),
        ],
    ],

    'features' => [
        'bump' => [
            'cooldown_minutes' => (int) env('MONETIZATION_BUMP_COOLDOWN', 60),
        ],
    ],
];
