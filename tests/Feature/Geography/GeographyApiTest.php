<?php

namespace Tests\Feature\Geography;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Geography\Concerns\CreatesGeography;
use Tests\TestCase;

class GeographyApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesGeography;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(), 'api');
    }

    public function test_can_list_countries_with_filters(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/countries');
        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        $filtered = $this->getJson('/api/geography/countries?name=Iran');
        $filtered
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $data['iran']->id);
    }

    public function test_can_show_country_with_related_data(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/countries/' . $data['iran']->id);
        $response
            ->assertOk()
            ->assertJsonPath('data.id', $data['iran']->id)
            ->assertJsonCount(2, 'data.provinces')
            ->assertJsonPath('data.capital_city.id', $data['tehranCity']->id);
    }

    public function test_can_list_provinces_with_filters(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/provinces?country_id=' . $data['iran']->id);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonMissing(['country_id' => $data['iraq']->id]);
    }

    public function test_can_show_province_with_related_data(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/provinces/' . $data['tehranProvince']->id);
        $response
            ->assertOk()
            ->assertJsonPath('data.country.id', $data['iran']->id)
            ->assertJsonCount(2, 'data.cities');
    }

    public function test_can_list_cities_with_filters(): void
    {
        $data = $this->seedGeography();

        $byName = $this->getJson('/api/geography/cities?name=Teh');
        $byName
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name_en', 'Tehran');

        $byCoordinates = $this->getJson('/api/geography/cities?latitude=35.6892&longitude=51.389');
        $byCoordinates
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.id', $data['tehranCity']->id);
    }

    public function test_can_show_city_with_relationships(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/cities/' . $data['tehranCity']->id);
        $response
            ->assertOk()
            ->assertJsonPath('data.province.id', $data['tehranProvince']->id)
            ->assertJsonPath('data.province.country.id', $data['iran']->id);
    }

    public function test_can_list_cities_for_province(): void
    {
        $data = $this->seedGeography();

        $response = $this->getJson('/api/geography/provinces/' . $data['tehranProvince']->id . '/cities');
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2);
    }

    public function test_geography_endpoints_return_persian_labels(): void
    {
        $data = $this->seedGeography();

        $countryResponse = $this->getJson('/api/geography/countries/' . $data['iran']->id);
        $countryResponse
            ->assertOk()
            ->assertJsonFragment(['name' => 'ایران'])
            ->assertJsonFragment(['name' => 'تهران'])
            ->assertJsonFragment(['name' => 'اصفهان']);

        $cityResponse = $this->getJson('/api/geography/cities/' . $data['karajCity']->id);
        $cityResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'کرج')
            ->assertJsonPath('data.province.name', 'تهران');
    }

}
