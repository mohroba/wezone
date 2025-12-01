<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanPriceRuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plan_id' => $this->plan_id,
            'advertisable_type_id' => $this->advertisable_type_id,
            'ad_category_id' => $this->ad_category_id,
            'override_price' => $this->override_price,
            'currency' => $this->currency,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'discount_starts_at' => optional($this->discount_starts_at)->toIso8601String(),
            'discount_ends_at' => optional($this->discount_ends_at)->toIso8601String(),
            'usage_cap' => $this->usage_cap,
            'usage_count' => $this->usage_count,
            'is_stackable' => $this->is_stackable,
            'metadata' => $this->metadata,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
            'discount_codes' => DiscountCodeResource::collection($this->whenLoaded('discountCodes')),
        ];
    }
}
