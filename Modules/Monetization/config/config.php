<?php

return [
    'default_gateway' => env('PAYMENT_GATEWAY', 'zarinpal'),

    'features' => [
        'urgent_badge' => [
            'column' => 'urgent_until',
            'duration_key' => 'urgent_duration',
        ],
        'highlight' => [
            'column' => 'is_highlighted',
        ],
        'bump' => [
            'column' => 'last_bumped_at',
            'cooldown_minutes' => env('PLAN_BUMP_COOLDOWN', 180),
            'allowance_column' => 'bump_allowance',
        ],
    ],

    'gateways' => [
        'zarinpal' => [
            'driver' => \Modules\Monetization\Domain\Gateways\ZarinpalGateway::class,
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
            'callback_url' => env('ZARINPAL_CALLBACK_URL'),
            'sandbox' => (bool) env('ZARINPAL_SANDBOX', true),
        ],
        'idpay' => [
            'driver' => \Modules\Monetization\Domain\Gateways\IDPayGateway::class,
            'api_key' => env('IDPAY_API_KEY'),
            'sandbox' => (bool) env('IDPAY_SANDBOX', true),
            'callback_url' => env('IDPAY_CALLBACK_URL'),
        ],
        'stripe' => [
            'driver' => \Modules\Monetization\Domain\Gateways\StripeGateway::class,
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'wallet' => [
            'driver' => null,
        ],
    ],
];
