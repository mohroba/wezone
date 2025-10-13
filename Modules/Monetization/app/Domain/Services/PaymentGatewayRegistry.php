<?php

namespace Modules\Monetization\Domain\Services;

use Modules\Monetization\Domain\Contracts\PaymentGatewayInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class PaymentGatewayRegistry
{
    public function __construct(private readonly Container $container)
    {
    }

    public function resolve(?string $name = null): PaymentGatewayInterface
    {
        $name = $name ?? Config::get('monetization.default_gateway');
        $config = Config::get("monetization.gateways.$name");

        if (! $config || empty($config['driver'])) {
            throw new InvalidArgumentException("Gateway [$name] is not configured.");
        }

        $gateway = $this->container->make($config['driver']);
        if (! $gateway instanceof PaymentGatewayInterface) {
            throw new InvalidArgumentException("Gateway [$name] is invalid.");
        }

        if (method_exists($gateway, 'setConfig')) {
            $gateway->setConfig($config);
        }

        return $gateway;
    }
}
