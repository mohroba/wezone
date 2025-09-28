<?php

namespace Tests\Feature\Geography;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeographyApiTest extends TestCase
{
    use RefreshDatabase;

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

    private function seedGeography(): array
    {
        $iran = Country::create([
            'capital_city' => null,
            'name' => 'ایران',
            'name_en' => 'Iran',
        ]);

        $iraq = Country::create([
            'capital_city' => null,
            'name' => 'عراق',
            'name_en' => 'Iraq',
        ]);

        $tehranProvince = Province::create([
            'country' => $iran->id,
            'name' => 'تهران',
            'name_en' => 'Tehran',
        ]);

        $isfahanProvince = Province::create([
            'country' => $iran->id,
            'name' => 'اصفهان',
            'name_en' => 'Isfahan',
        ]);

        $baghdadProvince = Province::create([
            'country' => $iraq->id,
            'name' => 'بغداد',
            'name_en' => 'Baghdad',
        ]);

        $tehranCity = City::create([
            'province' => $tehranProvince->id,
            'name' => 'تهران',
            'name_en' => 'Tehran',
            'latitude' => 35.68920000,
            'longitude' => 51.38900000,
        ]);

        $karajCity = City::create([
            'province' => $tehranProvince->id,
            'name' => 'کرج',
            'name_en' => 'Karaj',
            'latitude' => 35.83270000,
            'longitude' => 50.99150000,
        ]);

        $isfahanCity = City::create([
            'province' => $isfahanProvince->id,
            'name' => 'اصفهان',
            'name_en' => 'Isfahan',
            'latitude' => 32.65250000,
            'longitude' => 51.67460000,
        ]);

        $baghdadCity = City::create([
            'province' => $baghdadProvince->id,
            'name' => 'بغداد',
            'name_en' => 'Baghdad',
            'latitude' => 33.31520000,
            'longitude' => 44.36610000,
        ]);

        $iran->update(['capital_city' => $tehranCity->id]);
        $iraq->update(['capital_city' => $baghdadCity->id]);

        return compact(
            'iran',
            'iraq',
            'tehranProvince',
            'isfahanProvince',
            'baghdadProvince',
            'tehranCity',
            'karajCity',
            'isfahanCity',
            'baghdadCity'
        );
    }
}
