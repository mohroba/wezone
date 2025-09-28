<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\City */
class CityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'province_id' => $this->province,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'province' => ProvinceResource::make($this->whenLoaded('provinceRelation')),
        ];
    }
}
