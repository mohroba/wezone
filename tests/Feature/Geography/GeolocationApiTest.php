<?php

namespace Tests\Feature\Geography;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Geography\Concerns\CreatesGeography;
use Tests\TestCase;

class GeolocationApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesGeography;

    public function test_can_lookup_locations_by_coordinates(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/locations/lookup?latitude=35.7&longitude=51.4&radius_km=80&city_limit=5&province_limit=5');

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($data) {
                $json
                    ->where('meta.city_count', 2)
                    ->where('meta.province_count', 1)
                    ->where('data.cities.0.id', $data['tehranCity']->id)
                    ->where('data.provinces.0.id', $data['tehranProvince']->id)
                    ->has('data.cities.0.distance_km')
                    ->has('data.provinces.0.distance_km');
            });
    }

    public function test_can_resolve_user_city_by_coordinates(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/locations/user-city?latitude=35.7&longitude=51.4&max_distance_km=80');

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($data) {
                $json
                    ->where('data.id', $data['tehranCity']->id)
                    ->where('data.name_en', 'Tehran')
                    ->where('meta.max_distance_km', fn ($value) => (float) $value === 80.0)
                    ->where('meta.distance_km', fn ($distance) => $distance < 15);
            });
    }

    public function test_can_list_nearby_cities_sorted_by_distance(): void
    {
        $this->seedGeography();

        $response = $this->getJson('/api/geography/locations/nearby-cities?latitude=35.7&longitude=51.4&radius_km=400&limit=3');

        $response
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->has('data', 3)
                    ->where('data.0.name_en', 'Tehran')
                    ->where('data.1.name_en', 'Karaj')
                    ->where('data.2.name_en', 'Isfahan')
                    ->where('meta.count', 3)
                    ->where('meta.radius_km', fn ($value) => (float) $value === 400.0)
                    ->where('data.0.distance_km', fn ($distance) => $distance < 15);
            });
    }
}
