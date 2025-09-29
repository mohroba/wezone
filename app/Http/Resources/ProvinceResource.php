<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Province */
class ProvinceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'distance_km' => $this->when(isset($this->distance_km), round((float) $this->distance_km, 3)),
            'country' => CountryResource::make($this->whenLoaded('countryRelation')),
            'cities' => CityResource::collection($this->whenLoaded('cities')),
        ];
    }
}
