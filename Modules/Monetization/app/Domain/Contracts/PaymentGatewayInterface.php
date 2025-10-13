<?php

namespace Modules\Monetization\Domain\Contracts;

use Modules\Monetization\Domain\DTO\GatewayInitiationData;
use Modules\Monetization\Domain\DTO\GatewayRefundData;
use Modules\Monetization\Domain\DTO\GatewayVerificationData;
use Modules\Monetization\Domain\Entities\Payment;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function initiate(GatewayInitiationData $data): Payment;

    public function verify(GatewayVerificationData $data): Payment;

    public function refund(GatewayRefundData $data): Payment;
}
