<?php

namespace Modules\Ad\Advertisable\Definitions;

use Modules\Ad\Advertisable\DTO\AdvertisablePropertyDefinition;
use Modules\Ad\Models\AdCar;

final class CarAdvertisableTypeDefinition extends AbstractAdvertisableTypeDefinition
{
    public function key(): string
    {
        return 'car';
    }

    public function label(): string
    {
        return 'Car';
    }

    public function modelClass(): string
    {
        return AdCar::class;
    }

    public function description(): ?string
    {
        return 'Passenger vehicles, including sedans, hatchbacks, SUVs, and similar listings.';
    }

    /**
     * @return array<int, AdvertisablePropertyDefinition>
     */
    protected function defineBaseProperties(): array
    {
        return [
            new AdvertisablePropertyDefinition('slug', 'string', 'Slug', 'Unique, URL-friendly identifier for the car listing.', true, ['string', 'max:255']),
            new AdvertisablePropertyDefinition('brand_id', 'integer', 'Brand', 'Identifier referencing the car manufacturer.'),
            new AdvertisablePropertyDefinition('model_id', 'integer', 'Model', 'Identifier referencing the specific car model or trim.'),
            new AdvertisablePropertyDefinition('year', 'integer', 'Manufacture Year', 'Production year of the vehicle.'),
            new AdvertisablePropertyDefinition('mileage', 'integer', 'Mileage', 'Recorded distance travelled, typically in kilometers.'),
            new AdvertisablePropertyDefinition('fuel_type', 'string', 'Fuel Type', 'Energy source powering the vehicle, such as petrol or electric.'),
            new AdvertisablePropertyDefinition('transmission', 'string', 'Transmission', 'Gearbox type, e.g. automatic or manual.'),
            new AdvertisablePropertyDefinition('body_style', 'string', 'Body Style', 'Vehicle body classification such as sedan or SUV.'),
            new AdvertisablePropertyDefinition('color', 'string', 'Color', 'Exterior colour of the car.'),
            new AdvertisablePropertyDefinition('condition', 'string', 'Condition', 'Overall condition such as new or used.'),
            new AdvertisablePropertyDefinition('ownership_count', 'integer', 'Previous Owners', 'Number of recorded owners for the vehicle.'),
            new AdvertisablePropertyDefinition('vin', 'string', 'VIN', 'Vehicle identification number, if available.'),
            new AdvertisablePropertyDefinition('registration_expiry', 'date', 'Registration Expiry', 'Date when the current registration expires.'),
            new AdvertisablePropertyDefinition('insurance_expiry', 'date', 'Insurance Expiry', 'Date when the current insurance policy expires.'),
        ];
    }
}
