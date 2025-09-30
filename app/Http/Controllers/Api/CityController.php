<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Geography\CityIndexRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    /**
     * List cities
     *
     * Returns a paginated list of cities.
     *
     * @group Geography
     * @authenticated
     *
     * @queryParam id integer optional Filter by city ID. Example: 10
     * @queryParam name string optional Filter by city name (fa/en, partial match). Example: ""
     * @queryParam name_en string optional Filter by English name (partial match). Example: ""
     * @queryParam province_id integer optional Filter by province ID. Example: 2
     * @queryParam country_id integer optional Filter by country ID. Example: 1
     * @queryParam latitude number optional Filter by exact latitude. Example: 35.6892
     * @queryParam longitude number optional Filter by exact longitude. Example: 51.3890
     * @queryParam per_page integer optional Results per page (1-100). Defaults to 50. Example: 25
     */
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

    /**
     * Get a city
     *
     * Return details for a single city, including its province and country.
     *
     * @group Geography
     * @authenticated
     *
     * @urlParam city integer required The ID of the city. Example: 10
     */
    public function show(City $city): CityResource
    {
        $city->load('provinceRelation.countryRelation.capitalCity');

        return new CityResource($city);
    }
}
