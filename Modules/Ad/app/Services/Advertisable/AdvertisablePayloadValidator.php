<?php

namespace Modules\Ad\Services\Advertisable;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Ad\Models\AdCar;
use Modules\Ad\Models\AdJob;
use Modules\Ad\Models\AdRealEstate;

class AdvertisablePayloadValidator
{
    /**
     * @param class-string $type
     * @param array<string, mixed> $attributes
     *
     * @return array<string, mixed>
     *
     * @throws ValidationException
     */
    public function validate(string $type, array $attributes): array
    {
        $rules = $this->rulesFor($type);
        $messages = $this->messagesFor($type);

        $validator = Validator::make($attributes, $rules, $messages);
        $validator->stopOnFirstFailure(false);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * @param class-string $type
     *
     * @return array<string, mixed>
     */
    private function rulesFor(string $type): array
    {
        $currentYear = (int) date('Y') + 1;

        return match ($type) {
            AdCar::class => [
                'brand_id' => ['required', 'integer', 'min:1'],
                'model_id' => ['required', 'integer', 'min:1'],
                'year' => ['required', 'integer', 'between:1900,' . $currentYear],
                'mileage' => ['nullable', 'integer', 'min:0'],
                'fuel_type' => ['nullable', 'string', 'max:255'],
                'transmission' => ['nullable', 'string', 'max:255'],
                'body_style' => ['nullable', 'string', 'max:255'],
                'color' => ['nullable', 'string', 'max:255'],
                'condition' => ['nullable', 'string', 'max:255'],
                'ownership_count' => ['nullable', 'integer', 'min:0'],
                'vin' => ['nullable', 'string', 'max:64'],
                'registration_expiry' => ['nullable', 'date'],
                'insurance_expiry' => ['nullable', 'date'],
                'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            ],
            AdRealEstate::class => [
                'property_type' => ['required', 'string', 'max:255'],
                'usage_type' => ['required', 'string', 'max:255'],
                'area_m2' => ['required', 'numeric', 'min:0'],
                'land_area_m2' => ['nullable', 'numeric', 'min:0'],
                'bedrooms' => ['nullable', 'integer', 'min:0'],
                'bathrooms' => ['nullable', 'integer', 'min:0'],
                'parking_spaces' => ['nullable', 'integer', 'min:0'],
                'floor_number' => ['nullable', 'integer'],
                'total_floors' => ['nullable', 'integer', 'min:0'],
                'year_built' => ['nullable', 'integer', 'between:1800,' . $currentYear],
                'document_type' => ['nullable', 'string', 'max:255'],
                'has_elevator' => ['nullable', 'boolean'],
                'has_storage' => ['nullable', 'boolean'],
                'utilities_json' => ['nullable', 'array'],
                'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            ],
            AdJob::class => [
                'company_name' => ['required', 'string', 'max:255'],
                'position_title' => ['required', 'string', 'max:255'],
                'industry' => ['nullable', 'string', 'max:255'],
                'employment_type' => ['required', 'string', 'max:255'],
                'experience_level' => ['nullable', 'string', 'max:255'],
                'education_level' => ['nullable', 'string', 'max:255'],
                'salary_min' => ['nullable', 'integer', 'min:0'],
                'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
                'currency' => ['nullable', 'string', 'size:3'],
                'salary_type' => ['nullable', 'string', 'max:255'],
                'work_schedule' => ['nullable', 'string', 'max:255'],
                'remote_level' => ['nullable', 'string', 'max:255'],
                'benefits_json' => ['nullable', 'array'],
                'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            ],
            default => [],
        };
    }

    /**
     * @param class-string $type
     *
     * @return array<string, string>
     */
    private function messagesFor(string $type): array
    {
        if ($type === AdJob::class) {
            return [
                'salary_max.gte' => 'The salary maximum must be greater than or equal to the salary minimum.',
            ];
        }

        return [];
    }
}
