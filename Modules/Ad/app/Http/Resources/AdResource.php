<?php

namespace Modules\Ad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Ad\Models\Ad */
class AdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'advertisable_type' => $this->advertisable_type,
            'advertisable_id' => $this->advertisable_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'status' => $this->status,
            'published_at' => $this->published_at?->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),
            'price_amount' => $this->price_amount,
            'price_currency' => $this->price_currency,
            'is_negotiable' => $this->is_negotiable,
            'is_exchangeable' => $this->is_exchangeable,
            'city_id' => $this->city_id,
            'province_id' => $this->province_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'contact_channel' => $this->contact_channel,
            'view_count' => $this->view_count,
            'share_count' => $this->share_count,
            'favorite_count' => $this->favorite_count,
            'featured_until' => $this->featured_until?->toISOString(),
            'priority_score' => $this->priority_score,
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'depth' => $category->depth,
                        'path' => $category->path,
                        'is_active' => $category->is_active,
                        'pivot' => [
                            'is_primary' => (bool) $category->pivot->is_primary,
                            'assigned_by' => $category->pivot->assigned_by,
                        ],
                    ];
                });
            }),
            'advertisable' => $this->whenLoaded('advertisable', function () {
                return $this->advertisable?->toArray();
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
