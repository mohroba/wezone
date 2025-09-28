<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Geography\CityIndexRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    public function index(CityIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $perPage = $filters['per_page'] ?? 50;

        $cities = City::query()
            ->with('provinceRelation.countryRelation')
            ->when(isset($filters['id']), fn ($query) => $query->whereKey($filters['id']))
            ->when(isset($filters['name']), function ($query) use ($filters) {
                $name = $filters['name'];
                $query->where(function ($inner) use ($name) {
                    $inner
                        ->where('name', 'like', '%' . $name . '%')
                        ->orWhere('name_en', 'like', '%' . $name . '%');
                });
            })
            ->when(isset($filters['name_en']), fn ($query) => $query->where('name_en', 'like', '%' . $filters['name_en'] . '%'))
            ->when(isset($filters['province_id']), fn ($query) => $query->where('province', $filters['province_id']))
            ->when(isset($filters['country_id']), function ($query) use ($filters) {
                $query->whereHas('provinceRelation', fn ($provinceQuery) => $provinceQuery->where('country', $filters['country_id']));
            })
            ->when(isset($filters['latitude']), fn ($query) => $query->where('latitude', $filters['latitude']))
            ->when(isset($filters['longitude']), fn ($query) => $query->where('longitude', $filters['longitude']))
            ->orderBy('name_en')
            ->paginate($perPage)
            ->withQueryString();

        return CityResource::collection($cities);
    }

    public function show(City $city): CityResource
    {
        $city->load('provinceRelation.countryRelation.capitalCity');

        return new CityResource($city);
    }
}
