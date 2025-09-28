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

    public function show(Province $province): ProvinceResource
    {
        $province->load([
            'countryRelation.capitalCity',
            'cities' => fn ($query) => $query->orderBy('name_en'),
        ]);

        return new ProvinceResource($province);
    }

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
