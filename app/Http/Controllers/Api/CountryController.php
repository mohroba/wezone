<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Geography\CountryIndexRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    /**
     * List countries
     *
     * Returns a paginated list of countries.
     *
     * @group Geography
     * @authenticated
     *
     * @queryParam id integer optional Filter by country ID. Example: 1
     * @queryParam name string optional Filter by country name (fa/en, partial match). Example: ""
     * @queryParam name_en string optional Filter by English name (partial match). Example: ""
     * @queryParam capital_city integer optional Filter by capital city ID. Example: 10
     * @queryParam per_page integer optional Results per page (1-100). Defaults to 50. Example: 25
     */
    public function index(CountryIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $perPage = $filters['per_page'] ?? 50;

        $countries = Country::query()
            ->with('capitalCity')
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
            ->when(isset($filters['capital_city']), fn ($query) => $query->where('capital_city', $filters['capital_city']))
            ->orderBy('name_en')
            ->paginate($perPage)
            ->withQueryString();

        return CountryResource::collection($countries);
    }

    /**
     * Get a country
     *
     * Return details for a single country, including its capital and provinces.
     *
     * @group Geography
     * @authenticated
     *
     * @urlParam country integer required The ID of the country. Example: 1
     */
    public function show(Country $country): CountryResource
    {
        $country->load([
            'capitalCity.provinceRelation',
            'provinces' => fn ($query) => $query->orderBy('name_en'),
        ]);

        return new CountryResource($country);
    }
}
