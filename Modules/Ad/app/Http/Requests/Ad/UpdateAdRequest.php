<?php

namespace Modules\Ad\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\Ad;
use Modules\Ad\Support\AdvertisableType;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Table name without needing a bound model (safe during scribe:generate)
        $table = (new Ad())->getTable();

        // Route param may be a model, an ID, or null (eg. during docs generation)
        $routeParam = $this->route('ad');
        $adId = $routeParam instanceof Ad
            ? $routeParam->getKey()
            : (is_numeric($routeParam) ? (int) $routeParam : null);

        // Build unique rule safely; only ignore when we actually have an ID
        $slugUnique = Rule::unique($table, 'slug');
        if ($adId !== null) {
            $slugUnique->ignore($adId);
        }

        $statusValues = ['draft', 'pending_review', 'published', 'rejected', 'archived', 'expired'];

        return [
            'user_id'            => ['sometimes', 'required', 'exists:users,id'],
            'advertisable_type'  => ['sometimes', 'required', 'string', Rule::in(AdvertisableType::allowed())],
            'advertisable_id'    => ['sometimes', 'required', 'integer'],
            'slug'               => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash', $slugUnique],
            'title'              => ['sometimes', 'required', 'string', 'max:255'],
            'subtitle'           => ['nullable', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'status'             => ['sometimes', 'required', 'string', Rule::in($statusValues)],
            'published_at'       => ['nullable', 'date'],
            'expires_at'         => ['nullable', 'date', 'after_or_equal:published_at'],
            'price_amount'       => ['nullable', 'integer'],
            'price_currency'     => ['nullable', 'string', 'size:3'],
            'is_negotiable'      => ['boolean'],
            'is_exchangeable'    => ['boolean'],
            'city_id'            => ['nullable', 'exists:cities,id'],
            'province_id'        => ['nullable', 'exists:provinces,id'],
            'latitude'           => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'          => ['nullable', 'numeric', 'between:-180,180'],
            'contact_channel'    => ['nullable', 'array'],
            'view_count'         => ['nullable', 'integer', 'min:0'],
            'share_count'        => ['nullable', 'integer', 'min:0'],
            'favorite_count'     => ['nullable', 'integer', 'min:0'],
            'featured_until'     => ['nullable', 'date'],
            'priority_score'     => ['nullable', 'numeric'],
            'categories'         => ['nullable', 'array'],
            'categories.*.id'    => ['required', 'integer', 'exists:ad_categories,id'],
            'categories.*.is_primary' => ['nullable', 'boolean'],
            'categories.*.assigned_by' => ['nullable', 'integer', 'exists:users,id'],
            'status_note'        => ['nullable', 'string'],
            'status_metadata'    => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_negotiable'   => $this->toBoolean($this->input('is_negotiable')),
            'is_exchangeable' => $this->toBoolean($this->input('is_exchangeable')),
        ]);
    }

    public function withValidator($validator): void
    {
        // Use current ad values as defaults only if we actually have a bound model
        $routeParam = $this->route('ad');
        $ad = $routeParam instanceof Ad ? $routeParam : null;

        $type = $this->input('advertisable_type', $ad?->advertisable_type);
        $advertisableId = $this->input('advertisable_id', $ad?->advertisable_id);

        if ($type && AdvertisableType::isAllowed($type) && $advertisableId) {
            $table = AdvertisableType::tableFor($type);
            $exists = DB::table($table)->where('id', $advertisableId)->exists();

            if (! $exists) {
                $validator->errors()->add('advertisable_id', 'The selected advertisable does not exist.');
            }
        }
    }

    /**
     * Scribe v5.x: describe complex/nested fields so the docs UI renders them properly.
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => [
                'description' => 'Identifier of the ad owner.',
                'type'        => 'integer',
                'example'     => 42,
                'required'    => false,
            ],
            'advertisable_type' => [
                'description' => 'Fully qualified class name of the advertisable subtype.',
                'type'        => 'string',
                'example'     => 'Modules\\Ad\\Models\\AdCar',
                'required'    => false,
            ],
            'advertisable_id' => [
                'description' => 'Identifier of the advertisable record.',
                'type'        => 'integer',
                'example'     => 10,
                'required'    => false,
            ],
            'slug' => [
                'description' => 'Unique slug for the ad.',
                'type'        => 'string',
                'example'     => 'peugeot-206-2024',
                'required'    => false,
            ],
            'title' => [
                'description' => 'Headline displayed for the ad.',
                'type'        => 'string',
                'example'     => 'Peugeot 206 2024',
                'required'    => false,
            ],
            'subtitle' => [
                'description' => 'Optional subtitle or tagline.',
                'type'        => 'string',
                'example'     => 'Full options, low mileage',
            ],
            'description' => [
                'description' => 'Rich description of the listing.',
                'type'        => 'string',
                'example'     => 'One owner, regularly serviced, ready to drive.',
            ],
            'status' => [
                'description' => 'Lifecycle status for moderation.',
                'type'        => 'string',
                'example'     => 'published',
                'required'    => false,
            ],
            'published_at' => [
                'description' => 'Publication datetime in ISO 8601 format.',
                'type'        => 'string',
                'example'     => '2024-05-01T08:00:00Z',
            ],
            'expires_at' => [
                'description' => 'Optional expiration datetime in ISO 8601 format.',
                'type'        => 'string',
                'example'     => '2024-06-01T08:00:00Z',
            ],
            'price_amount' => [
                'description' => 'Price stored in the smallest currency unit.',
                'type'        => 'integer',
                'example'     => 450000000,
            ],
            'price_currency' => [
                'description' => 'Three-letter ISO currency code.',
                'type'        => 'string',
                'example'     => 'IRR',
            ],
            'is_negotiable' => [
                'description' => 'Indicates if the price can be negotiated.',
                'type'        => 'boolean',
                'example'     => true,
            ],
            'is_exchangeable' => [
                'description' => 'Indicates if swaps are accepted.',
                'type'        => 'boolean',
                'example'     => false,
            ],
            'city_id' => [
                'description' => 'City identifier for the ad location.',
                'type'        => 'integer',
                'example'     => 3,
            ],
            'province_id' => [
                'description' => 'Province identifier for the ad location.',
                'type'        => 'integer',
                'example'     => 1,
            ],
            'latitude' => [
                'description' => 'Latitude coordinate of the listing.',
                'type'        => 'number',
                'example'     => 35.6892,
            ],
            'longitude' => [
                'description' => 'Longitude coordinate of the listing.',
                'type'        => 'number',
                'example'     => 51.3890,
            ],
            'contact_channel' => [
                'description' => 'Contact details such as phone or messenger usernames.',
                'type'        => 'object',
                'example'     => ['phone' => '123456789', 'telegram' => '@majid'],
            ],
            'view_count' => [
                'description' => 'Pre-set view counter value, typically managed internally.',
                'type'        => 'integer',
                'example'     => 100,
            ],
            'share_count' => [
                'description' => 'Pre-set share counter value, typically managed internally.',
                'type'        => 'integer',
                'example'     => 10,
            ],
            'favorite_count' => [
                'description' => 'Pre-set favorite counter value, typically managed internally.',
                'type'        => 'integer',
                'example'     => 25,
            ],
            'featured_until' => [
                'description' => 'Datetime until which the ad remains featured.',
                'type'        => 'string',
                'example'     => '2024-05-15T08:00:00Z',
            ],
            'priority_score' => [
                'description' => 'Numeric score affecting ordering.',
                'type'        => 'number',
                'example'     => 12.5,
            ],
            'categories' => [
                'description' => 'Array of category assignments.',
                'type'        => 'object[]',
                'example'     => [
                    ['id' => 7, 'is_primary' => true,  'assigned_by' => 42],
                    ['id' => 12, 'is_primary' => false],
                ],
            ],
            'categories[].id' => [
                'description' => 'Category ID.',
                'type'        => 'integer',
                'example'     => 7,
            ],
            'categories[].is_primary' => [
                'description' => 'Whether this is the primary category.',
                'type'        => 'boolean',
                'example'     => true,
            ],
            'categories[].assigned_by' => [
                'description' => 'User ID who assigned this category.',
                'type'        => 'integer',
                'example'     => 42,
            ],
            'status_note' => [
                'description' => 'Optional note saved alongside status changes.',
                'type'        => 'string',
                'example'     => 'Approved by moderator',
            ],
            'status_metadata' => [
                'description' => 'Structured metadata explaining the status change.',
                'type'        => 'object',
                'example'     => ['moderator' => 'system'],
            ],
        ];
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
