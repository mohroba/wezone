<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Country */
class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'name_en' => $this->name_en ?? '',
            'capital_city_id' => $this->capital_city,
            'capital_city' => CityResource::make($this->whenLoaded('capitalCity')),
            'provinces' => ProvinceResource::collection($this->whenLoaded('provinces')),
        ];
    }
}
