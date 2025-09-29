<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Geography\LocationLookupRequest;
use App\Http\Requests\Geography\NearbyCitiesRequest;
use App\Http\Requests\Geography\ResolveCityRequest;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProvinceResource;
use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class GeolocationController extends Controller
{
    private const DEFAULT_RADIUS_KM = 50.0;
    private const DEFAULT_LIMIT = 10;
    private const DEGREE_TO_RADIAN = 0.017453292519943295;

    public function lookup(LocationLookupRequest $request): JsonResponse
    {
        $data = $request->validated();
        $latitude = (float) $data['latitude'];
        $longitude = (float) $data['longitude'];
        $radius = array_key_exists('radius_km', $data) ? (float) $data['radius_km'] : self::DEFAULT_RADIUS_KM;
        $cityLimit = (int) ($data['city_limit'] ?? self::DEFAULT_LIMIT);
        $provinceLimit = (int) ($data['province_limit'] ?? self::DEFAULT_LIMIT);

        $cities = $this->filterByRadius(
            $this
                ->buildCityDistanceQuery($latitude, $longitude, $radius)
                ->orderBy('distance_km')
                ->limit($cityLimit)
                ->with('provinceRelation.countryRelation')
                ->get(),
            $radius
        );

        $provinces = $this->filterByRadius(
            $this
                ->buildProvinceDistanceQuery($latitude, $longitude, $radius)
                ->orderBy('distance_km')
                ->limit($provinceLimit)
                ->with('countryRelation')
                ->get(),
            $radius
        );

        return response()->json([
            'data' => [
                'cities' => CityResource::collection($cities)->resolve(),
                'provinces' => ProvinceResource::collection($provinces)->resolve(),
            ],
            'meta' => [
                'city_count' => $cities->count(),
                'province_count' => $provinces->count(),
                'radius_km' => $radius,
            ],
        ]);
    }

    public function resolveUserCity(ResolveCityRequest $request): JsonResponse|CityResource
    {
        $data = $request->validated();
        $latitude = (float) $data['latitude'];
        $longitude = (float) $data['longitude'];
        $maxDistance = array_key_exists('max_distance_km', $data) ? (float) $data['max_distance_km'] : self::DEFAULT_RADIUS_KM;

        $city = $this
            ->filterByRadius(
                $this
                    ->buildCityDistanceQuery($latitude, $longitude, $maxDistance)
                    ->orderBy('distance_km')
                    ->limit(1)
                    ->with('provinceRelation.countryRelation')
                    ->get(),
                $maxDistance
            )
            ->first();

        if ($city === null) {
            return response()->json([
                'message' => 'No city found within the specified distance.',
            ], 404);
        }

        return CityResource::make($city)->additional([
            'meta' => [
                'distance_km' => round((float) $city->distance_km, 3),
                'max_distance_km' => $maxDistance,
            ],
        ]);
    }

    public function nearbyCities(NearbyCitiesRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $latitude = (float) $data['latitude'];
        $longitude = (float) $data['longitude'];
        $radius = array_key_exists('radius_km', $data) ? (float) $data['radius_km'] : self::DEFAULT_RADIUS_KM;
        $limit = (int) ($data['limit'] ?? self::DEFAULT_LIMIT);

        $cities = $this->filterByRadius(
            $this
                ->buildCityDistanceQuery($latitude, $longitude, $radius)
                ->orderBy('distance_km')
                ->limit($limit)
                ->with('provinceRelation.countryRelation')
                ->get(),
            $radius
        );

        return CityResource::collection($cities)->additional([
            'meta' => [
                'count' => $cities->count(),
                'radius_km' => $radius,
            ],
        ]);
    }

    private function buildCityDistanceQuery(float $latitude, float $longitude, ?float $maxDistance = null): Builder
    {
        $distanceExpression = $this->distanceExpression('cities.latitude', 'cities.longitude');

        $query = City::query()
            ->select('cities.*')
            ->selectRaw($distanceExpression . ' AS distance_km', [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($maxDistance !== null) {
            $query->whereRaw($distanceExpression . ' <= ?', [$latitude, $longitude, $latitude, $maxDistance]);
        }

        return $query;
    }

    private function buildProvinceDistanceQuery(float $latitude, float $longitude, ?float $maxDistance = null): Builder
    {
        $distanceExpression = $this->distanceExpression('provinces.latitude', 'provinces.longitude');

        $query = Province::query()
            ->select('provinces.*')
            ->selectRaw($distanceExpression . ' AS distance_km', [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($maxDistance !== null) {
            $query->whereRaw($distanceExpression . ' <= ?', [$latitude, $longitude, $latitude, $maxDistance]);
        }

        return $query;
    }

    private function distanceExpression(string $latitudeColumn, string $longitudeColumn): string
    {
        return sprintf(
            '6371 * ACOS(MIN(1, MAX(-1, COS(? * %3$f) * COS(%1$s * %3$f) * COS((%2$s - ?) * %3$f) + SIN(? * %3$f) * SIN(%1$s * %3$f))))',
            $latitudeColumn,
            $longitudeColumn,
            self::DEGREE_TO_RADIAN
        );
    }

    private function filterByRadius(Collection $items, ?float $maxDistance): Collection
    {
        if ($maxDistance === null) {
            return $items->values();
        }

        return $items
            ->filter(fn ($item) => isset($item->distance_km) && (float) $item->distance_km <= $maxDistance)
            ->values();
    }
}
