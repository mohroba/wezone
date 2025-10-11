<?php

namespace Modules\Ad\Advertisable\Definitions;

use Modules\Ad\Advertisable\DTO\AdvertisablePropertyDefinition;
use Modules\Ad\Models\AdRealEstate;

final class RealEstateAdvertisableTypeDefinition extends AbstractAdvertisableTypeDefinition
{
    public function key(): string
    {
        return 'real_estate';
    }

    public function label(): string
    {
        return 'Real Estate';
    }

    public function modelClass(): string
    {
        return AdRealEstate::class;
    }

    public function description(): ?string
    {
        return 'Residential or commercial property listings including rentals and sales.';
    }

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    protected function defineBaseProperties(): array
    {
        return [
            new AdvertisablePropertyDefinition('slug', 'string', 'Slug', 'Unique, URL-friendly identifier for the property listing.', true, ['string', 'max:255']),
            new AdvertisablePropertyDefinition('property_type', 'string', 'Property Type', 'Type of property such as apartment, villa, or office.'),
            new AdvertisablePropertyDefinition('usage_type', 'string', 'Usage Type', 'Intended usage such as residential or commercial.'),
            new AdvertisablePropertyDefinition('area_m2', 'decimal', 'Building Area', 'Covered area of the property in square meters.'),
            new AdvertisablePropertyDefinition('land_area_m2', 'decimal', 'Land Area', 'Total land area in square meters, if applicable.'),
            new AdvertisablePropertyDefinition('bedrooms', 'integer', 'Bedrooms', 'Number of bedrooms.'),
            new AdvertisablePropertyDefinition('bathrooms', 'integer', 'Bathrooms', 'Number of bathrooms.'),
            new AdvertisablePropertyDefinition('parking_spaces', 'integer', 'Parking Spaces', 'Number of available parking spots.'),
            new AdvertisablePropertyDefinition('floor_number', 'integer', 'Floor Number', 'Current floor of the unit.'),
            new AdvertisablePropertyDefinition('total_floors', 'integer', 'Total Floors', 'Total floors in the building or complex.'),
            new AdvertisablePropertyDefinition('year_built', 'integer', 'Year Built', 'Construction completion year.'),
            new AdvertisablePropertyDefinition('document_type', 'string', 'Document Type', 'Legal document type provided for the property.'),
            new AdvertisablePropertyDefinition('has_elevator', 'boolean', 'Has Elevator', 'Indicates if the building is equipped with an elevator.'),
            new AdvertisablePropertyDefinition('has_storage', 'boolean', 'Has Storage', 'Indicates if the property includes a storage room.'),
            new AdvertisablePropertyDefinition('utilities_json', 'json', 'Utilities', 'Structured representation of available utilities.'),
        ];
    }
}
