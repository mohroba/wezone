<?php

namespace Modules\Monetization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'duration_days' => $this->duration_days,
            'features' => $this->features,
            'price_overrides' => $this->price_overrides,
            'price_override_rules' => $this->whenLoaded('priceOverrides'),
            'bump_cooldown_minutes' => $this->bump_cooldown_minutes,
        ];
    }
}
