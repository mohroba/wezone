<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Geography\ProvinceCitiesRequest;
use App\Http\Requests\Geography\ProvinceIndexRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProvinceController extends Controller
{
    /**
     * List provinces
     *
     * Returns a paginated list of provinces.
     *
     * @group Geography
     * @authenticated
     *
     * @queryParam id integer optional Filter by province ID. Example: 2
     * @queryParam name string optional Filter by province name (fa/en, partial match). Example: ""
     * @queryParam name_en string optional Filter by English name (partial match). Example: ""
     * @queryParam country_id integer optional Filter by country ID. Example: 1
     * @queryParam per_page integer optional Results per page (1-100). Defaults to 50. Example: 25
     */
    public function index(ProvinceIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $perPage = $filters['per_page'] ?? 50;

        $provinces = Province::query()
            ->with('countryRelation')
            ->when(isset($filters['id']), fn ($query) => $query->whereKey($filters['id']))
            ->when(isset($filters['name']), fn ($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(isset($filters['name_en']), fn ($query) => $query->where('name_en', 'like', '%' . $filters['name_en'] . '%'))
            ->when(isset($filters['country_id']), fn ($query) => $query->where('country', $filters['country_id']))
            ->orderBy('name_en')
            ->paginate($perPage)
            ->withQueryString();

        return ProvinceResource::collection($provinces);
    }

    /**
     * Get a province
     *
     * Return details for a single province, including its country and cities.
     *
     * @group Geography
     * @authenticated
     *
     * @urlParam province integer required The ID of the province. Example: 2
     */
    public function show(Province $province): ProvinceResource
    {
        $province->load([
            'countryRelation.capitalCity',
            'cities' => fn ($query) => $query->orderBy('name_en'),
        ]);

        return new ProvinceResource($province);
    }

    /**
     * List a province's cities
     *
     * Returns the cities that belong to the given province.
     *
     * @group Geography
     * @authenticated
     *
     * @urlParam province integer required The ID of the province. Example: 2
     * @queryParam per_page integer optional Results per page (1-100). Defaults to 50. Example: 25
     */
    public function cities(ProvinceCitiesRequest $request, Province $province): AnonymousResourceCollection
    {
        $perPage = $request->validated()['per_page'] ?? 50;

        $cities = $province
            ->cities()
            ->with('provinceRelation.countryRelation')
            ->orderBy('name_en')
            ->paginate($perPage)
            ->withQueryString();

        return CityResource::collection($cities);
    }
}
