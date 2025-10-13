<?php

namespace Modules\Ad\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Modules\Ad\Models\Ad;
use Modules\Ad\Services\Advertisable\AdvertisablePayloadValidator;
use Modules\Ad\Support\AdvertisableType;

class StoreAdRequest extends FormRequest
{
    private ?array $advertisablePayload = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusValues = ['draft', 'pending_review', 'published', 'rejected', 'archived', 'expired'];

        return [
            'user_id' => ['required', 'exists:users,id'],
            'advertisable' => ['required', 'array'],
            'advertisable.type' => ['required', 'string', Rule::in(AdvertisableType::allowed())],
            'advertisable.attributes' => ['required', 'array'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique((new Ad())->getTable(), 'slug')],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in($statusValues)],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'price_amount' => ['nullable', 'integer'],
            'price_currency' => ['nullable', 'string', 'size:3'],
            'is_negotiable' => ['boolean'],
            'is_exchangeable' => ['boolean'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'contact_channel' => ['nullable', 'array'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'share_count' => ['nullable', 'integer', 'min:0'],
            'favorite_count' => ['nullable', 'integer', 'min:0'],
            'featured_until' => ['nullable', 'date'],
            'priority_score' => ['nullable', 'numeric'],
            'categories' => ['nullable', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:ad_categories,id'],
            'categories.*.is_primary' => ['nullable', 'boolean'],
            'categories.*.assigned_by' => ['nullable', 'integer', 'exists:users,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['array'],
            'images.*.id' => ['nullable', 'integer', 'exists:media,id'],
            'images.*.file' => ['nullable', File::image()->max(5 * 1024)],
            'images.*.custom_properties' => ['nullable', 'array'],
            'images.*.custom_properties.alt' => ['nullable', 'string', 'max:255'],
            'images.*.custom_properties.caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function advertisablePayload(): array
    {
        if ($this->advertisablePayload !== null) {
            return $this->advertisablePayload;
        }

        $validated = $this->validated();

        return [
            'type' => Arr::get($validated, 'advertisable.type'),
            'attributes' => Arr::get($validated, 'advertisable.attributes', []),
        ];
    }

    public function prepareForValidation(): void
    {
        $payload = [];

        if ($this->exists('is_negotiable')) {
            $payload['is_negotiable'] = $this->toBoolean($this->input('is_negotiable'));
        }

        if ($this->exists('is_exchangeable')) {
            $payload['is_exchangeable'] = $this->toBoolean($this->input('is_exchangeable'));
        }

        if ($payload !== []) {
            $this->merge($payload);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $advertisable = $this->input('advertisable');

            if (is_array($advertisable)) {
                $type = Arr::get($advertisable, 'type');
                $attributes = Arr::get($advertisable, 'attributes', []);

                if (AdvertisableType::isAllowed($type)) {
                    try {
                        $validatedAttributes = app(AdvertisablePayloadValidator::class)
                            ->validate($type, is_array($attributes) ? $attributes : []);

                        $this->advertisablePayload = [
                            'type' => $type,
                            'attributes' => $validatedAttributes,
                        ];
                    } catch (ValidationException $exception) {
                        foreach ($exception->errors() as $field => $messages) {
                            foreach ($messages as $message) {
                                $validator->errors()->add("advertisable.attributes.$field", $message);
                            }
                        }
                    }
                }
            }

            $images = Arr::get($this->all(), 'images');

            if (is_array($images)) {
                foreach ($images as $index => $image) {
                    if (! is_array($image)) {
                        continue;
                    }

                    $file = $image['file'] ?? null;
                    if ($file === null && $this->hasFile("images.$index.file")) {
                        $file = $this->file("images.$index.file");
                    }

                    $hasFile = $file !== null;
                    $hasId = array_key_exists('id', $image) && $image['id'] !== null;

                    if (! $hasFile && ! $hasId) {
                        $validator->errors()->add("images.$index", 'Each image entry must contain a file upload or an existing media id.');
                    }
                }
            }
        });
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'user_id' => [
                'description' => 'Identifier of the ad owner.',
                'example' => 42,
            ],
            'advertisable' => [
                'description' => 'Payload describing the advertisable subtype and its attributes.',
                'example' => [
                    'type' => 'Modules\\Ad\\Models\\AdCar',
                    'attributes' => [
                        'brand_id' => 12,
                        'model_id' => 30,
                        'year' => 2023,
                    ],
                ],
            ],
            'slug' => [
                'description' => 'Unique slug for the ad.',
                'example' => 'peugeot-206-2024',
            ],
            'title' => [
                'description' => 'Headline displayed for the ad.',
                'example' => 'Peugeot 206 2024',
            ],
            'subtitle' => [
                'description' => 'Optional subtitle or tagline.',
                'example' => 'Full options, low mileage',
            ],
            'description' => [
                'description' => 'Rich description of the listing.',
                'example' => 'One owner, regularly serviced, ready to drive.',
            ],
            'status' => [
                'description' => 'Lifecycle status for moderation.',
                'example' => 'draft',
            ],
            'published_at' => [
                'description' => 'Publication datetime in ISO 8601 format.',
                'example' => '2024-05-01T08:00:00Z',
            ],
            'expires_at' => [
                'description' => 'Optional expiration datetime in ISO 8601 format.',
                'example' => '2024-06-01T08:00:00Z',
            ],
            'price_amount' => [
                'description' => 'Price stored in the smallest currency unit.',
                'example' => 450000000,
            ],
            'price_currency' => [
                'description' => 'Three-letter ISO currency code.',
                'example' => 'IRR',
            ],
            'is_negotiable' => [
                'description' => 'Indicates if the price can be negotiated.',
                'example' => true,
            ],
            'is_exchangeable' => [
                'description' => 'Indicates if swaps are accepted.',
                'example' => false,
            ],
            'city_id' => [
                'description' => 'City identifier for the ad location.',
                'example' => 3,
            ],
            'province_id' => [
                'description' => 'Province identifier for the ad location.',
                'example' => 1,
            ],
            'latitude' => [
                'description' => 'Latitude coordinate of the listing.',
                'example' => 35.6892,
            ],
            'longitude' => [
                'description' => 'Longitude coordinate of the listing.',
                'example' => 51.3890,
            ],
            'contact_channel' => [
                'description' => 'Contact details such as phone or messenger usernames.',
                'example' => ['phone' => '123456789'],
            ],
            'view_count' => [
                'description' => 'Pre-set view counter value, typically managed internally.',
                'example' => 0,
            ],
            'share_count' => [
                'description' => 'Pre-set share counter value, typically managed internally.',
                'example' => 0,
            ],
            'favorite_count' => [
                'description' => 'Pre-set favorite counter value, typically managed internally.',
                'example' => 0,
            ],
            'featured_until' => [
                'description' => 'Datetime until which the ad remains featured.',
                'example' => '2024-05-15T08:00:00Z',
            ],
            'priority_score' => [
                'description' => 'Numeric score affecting ordering.',
                'example' => 12.5,
            ],
            'categories' => [
                'description' => 'Array of category assignments.',
                'example' => [
                    ['id' => 7, 'is_primary' => true, 'assigned_by' => 42],
                ],
            ],
            'images' => [
                'description' => 'Ordered array of image uploads or existing media identifiers. When uploading files, send the request as multipart/form-data with fields such as images[0][file].',
                'example' => [
                    [
                        'file' => 'binary image upload',
                        'custom_properties' => ['alt' => 'Front view'],
                    ],
                    [
                        'id' => 15,
                        'custom_properties' => ['caption' => 'Detailed interior'],
                    ],
                ],
            ],
            'images[].file' => [
                'description' => 'Image file to attach to the ad. Either this or id is required per item. Provide the file via multipart/form-data.',
                'example' => 'photo.jpg',
            ],
            'images[].id' => [
                'description' => 'Existing media identifier to retain and optionally reorder.',
                'type' => 'integer',
                'example' => 42,
            ],
            'images[].custom_properties' => [
                'description' => 'Optional metadata saved alongside the image (e.g. alt text).',
                'type' => 'object',
                'example' => ['alt' => 'Side profile'],
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
