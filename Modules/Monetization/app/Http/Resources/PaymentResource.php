<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'gateway' => $this->gateway,
            'status' => $this->status,
            'ref_id' => $this->ref_id,
            'tracking_code' => $this->tracking_code,
            'paid_at' => optional($this->paid_at)->toIso8601String(),
        ];
    }
}
