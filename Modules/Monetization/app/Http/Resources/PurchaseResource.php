<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_status' => $this->payment_status,
            'payment_gateway' => $this->payment_gateway,
            'starts_at' => optional($this->starts_at)->toIso8601String(),
            'ends_at' => optional($this->ends_at)->toIso8601String(),
            'meta' => $this->meta,
        ];
    }
}
