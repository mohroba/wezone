<?php

namespace Modules\Monetization\Domain\Gateways;

abstract class AbstractGateway
{
    protected array $config = [];

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}
