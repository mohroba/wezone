<?php

namespace Tests\Feature\Geography;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GeographyModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_geography_tables_have_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('countries', ['id', 'capital_city', 'name', 'name_en']));
        $this->assertTrue(Schema::hasColumns('provinces', ['id', 'country', 'name', 'name_en']));
        $this->assertTrue(Schema::hasColumns('cities', ['id', 'province', 'name', 'name_en', 'latitude', 'longitude']));
    }

    public function test_country_province_city_relationships(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('PRAGMA foreign_keys = OFF');

        try {
            $country = Country::create([
                'capital_city' => null,
                'name' => 'ایران',
                'name_en' => 'Iran',
            ]);

            $province = Province::create([
                'country' => $country->id,
                'name' => 'تهران',
                'name_en' => 'Tehran',
            ]);

            $city = City::create([
                'province' => $province->id,
                'name' => 'تهران',
                'name_en' => 'Tehran',
                'latitude' => 35.6892,
                'longitude' => 51.3890,
            ]);

            $country->capital_city = $city->id;
            $country->save();
        } finally {
            DB::statement('PRAGMA foreign_keys = ON');
            Schema::enableForeignKeyConstraints();
        }

        $country->refresh();
        $province->refresh();
        $city->refresh();

        $this->assertTrue($country->provinces->contains($province));
        $this->assertTrue($province->cities->contains($city));
        $this->assertTrue($country->cities->contains($city));
        $this->assertTrue($province->countryRelation->is($country));

        $this->assertTrue($country->capitalCity->is($city));
        $this->assertSame(35.6892, $city->latitude);
        $this->assertSame(51.3890, $city->longitude);
    }
}
