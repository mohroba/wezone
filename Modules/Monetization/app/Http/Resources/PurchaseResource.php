<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Monetization\Http\Resources\PaymentResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'amount' => $this->amount,
            'list_price' => $this->list_price,
            'discounted_price' => $this->discounted_price,
            'currency' => $this->currency,
            'payment_status' => $this->payment_status,
            'payment_gateway' => $this->payment_gateway,
            'price_rule_id' => $this->price_rule_id,
            'discount_code_id' => $this->discount_code_id,
            'discount_code' => $this->discount_code,
            'bump_cooldown_minutes' => $this->bump_cooldown_minutes,
            'starts_at' => optional($this->starts_at)->toIso8601String(),
            'ends_at' => optional($this->ends_at)->toIso8601String(),
            'meta' => $this->meta,
            'payments' => $this->whenLoaded('payments', function () {
                return PaymentResource::collection($this->payments);
            }),
        ];
    }
}
