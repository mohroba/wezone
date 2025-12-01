<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountRedemptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount_code_id' => $this->discount_code_id,
            'plan_price_override_id' => $this->plan_price_override_id,
            'ad_plan_purchase_id' => $this->ad_plan_purchase_id,
            'user_id' => $this->user_id,
            'amount_before' => $this->amount_before,
            'amount_after' => $this->amount_after,
            'discount_amount' => $this->discount_amount,
            'redeemed_at' => optional($this->redeemed_at)->toIso8601String(),
            'meta' => $this->meta,
            'discount_code' => new DiscountCodeResource($this->whenLoaded('discountCode')),
            'price_rule' => new PlanPriceRuleResource($this->whenLoaded('priceRule')),
        ];
    }
}
