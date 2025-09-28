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
            'country' => CountryResource::make($this->whenLoaded('countryRelation')),
            'cities' => CityResource::collection($this->whenLoaded('cities')),
        ];
    }
}
