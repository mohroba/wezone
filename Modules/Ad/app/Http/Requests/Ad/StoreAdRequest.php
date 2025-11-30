<?php

namespace Modules\Ad\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Ad\Http\Requests\Concerns\ValidatesAttributeValueData;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdvertisableType as AdvertisableTypeModel;
use Modules\Ad\Services\Advertisable\AdvertisablePayloadValidator;
use Modules\Ad\Support\AdvertisableType as AdvertisableTypeSupport;

class StoreAdRequest extends FormRequest
{
    use ValidatesAttributeValueData;

    private ?array $advertisablePayload = null;
    private ?AdvertisableTypeModel $resolvedAdvertisableType = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusValues = ['draft', 'pending_review', 'published', 'rejected', 'archived', 'expired'];

        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'advertisable' => ['nullable', 'array'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique((new Ad())->getTable(), 'slug')],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in($statusValues)],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
            'price_amount' => ['nullable', 'integer'],
            'price_currency' => ['nullable', 'string', 'size:3'],
            'is_negotiable' => ['boolean'],
            'is_exchangeable' => ['boolean'],
            'comment_enable' => ['nullable', 'boolean'],
            'phone_enable' => ['nullable', 'boolean'],
            'chat_enable' => ['nullable', 'boolean'],
            'extra_amount' => ['required_if:is_exchangeable,true', 'integer', 'min:0'],
            'exchange_description' => ['required_if:is_exchangeable,true', 'string'],
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
            'categories' => ['sometimes', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:ad_categories,id'],
            'categories.*.is_primary' => ['nullable', 'boolean'],
            'categories.*.assigned_by' => ['nullable', 'integer', 'exists:users,id'],
            'attribute_values' => ['nullable', 'array'],
            'attribute_values.*.definition_id' => ['required', 'integer', 'exists:ad_attribute_definitions,id'],
            'attribute_values.*.value_string' => ['nullable', 'string'],
            'attribute_values.*.value_integer' => ['nullable', 'integer'],
            'attribute_values.*.value_decimal' => ['nullable', 'numeric'],
            'attribute_values.*.value_boolean' => ['nullable', 'boolean'],
            'attribute_values.*.value_date' => ['nullable', 'date'],
            'attribute_values.*.value_json' => ['nullable', 'array'],
        ];
    }

    public function advertisablePayload(): array
    {
        if ($this->advertisablePayload !== null) {
            return $this->advertisablePayload;
        }

        $validated = $this->validated();
        $advertisable = Arr::get($validated, 'advertisable');

        return is_array($advertisable) ? $advertisable : [];
    }

    public function advertisableTypeModel(): AdvertisableTypeModel
    {
        if ($this->resolvedAdvertisableType !== null) {
            return $this->resolvedAdvertisableType;
        }

        $typeId = (int) ($this->validated()['advertisable_type_id'] ?? $this->input('advertisable_type_id'));
        $this->resolvedAdvertisableType = AdvertisableTypeModel::query()->findOrFail($typeId);

        return $this->resolvedAdvertisableType;
    }

    public function prepareForValidation(): void
    {
        $payload = [];

        if (! $this->filled('user_id') && $this->user()) {
            $payload['user_id'] = $this->user()->getKey();
        }

        if (! $this->filled('status')) {
            $payload['status'] = 'draft';
        }

        $title = $this->input('title');
        if (! $title) {
            $payload['title'] = 'Draft';
            $title = 'Draft';
        }

        if (! $this->filled('slug')) {
            $payload['slug'] = Str::uuid()->toString();
        }

        if ($this->exists('is_negotiable')) {
            $payload['is_negotiable'] = $this->toBoolean($this->input('is_negotiable'));
        }

        if ($this->exists('is_exchangeable')) {
            $payload['is_exchangeable'] = $this->toBoolean($this->input('is_exchangeable'));
        }

        foreach (['comment_enable', 'phone_enable', 'chat_enable'] as $flag) {
            if ($this->exists($flag)) {
                $payload[$flag] = $this->toBoolean($this->input($flag));
            }
        }

        if ($this->has('categories') && is_array($this->input('categories'))) {
            $payload['categories'] = collect($this->input('categories'))
                ->map(function ($category) {
                    if (is_array($category) && array_key_exists('is_primary', $category)) {
                        $category['is_primary'] = $this->toBoolean($category['is_primary']);
                    }

                    return $category;
                })
                ->all();
        }

        if ($this->has('attribute_values') && is_array($this->input('attribute_values'))) {
            $payload['attribute_values'] = collect($this->input('attribute_values'))
                ->map(function ($value) {
                    if (is_array($value) && array_key_exists('value_boolean', $value)) {
                        $value['value_boolean'] = $this->toBoolean($value['value_boolean']);
                    }

                    return $value;
                })
                ->all();
        }

        if ($payload !== []) {
            $this->merge($payload);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $typeId = $this->input('advertisable_type_id');

            if (! $typeId) {
                return;
            }

            $type = AdvertisableTypeModel::query()->find($typeId);

            if (! $type) {
                $validator->errors()->add('advertisable_type_id', 'The selected advertisable type is invalid.');

                return;
            }

            $this->resolvedAdvertisableType = $type;
            $modelClass = $type->model_class;

            if (! AdvertisableTypeSupport::isAllowed($modelClass)) {
                $validator->errors()->add('advertisable_type_id', 'The selected advertisable type is not supported.');

                return;
            }

            $advertisableAttributes = $this->input('advertisable', []);

            if ($advertisableAttributes === null || $advertisableAttributes === []) {
                return;
            }

            if (! is_array($advertisableAttributes)) {
                $validator->errors()->add('advertisable', 'The advertisable field must be an object.');

                return;
            }

            try {
                $this->advertisablePayload = app(AdvertisablePayloadValidator::class)
                    ->validate($modelClass, $advertisableAttributes);
            } catch (ValidationException $exception) {
                foreach ($exception->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add("advertisable.$field", $message);
                    }
                }
            }

            $attributeValues = $this->input('attribute_values', []);

            if (is_array($attributeValues) && $attributeValues !== []) {
                $this->validateAttributeValuesAgainstType($validator, $attributeValues, (int) $type->getKey());
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
                'description' => 'Identifier of the ad owner. Defaults to the authenticated user when omitted.',
                'example' => 42,
            ],
            'advertisable_type_id' => [
                'description' => 'Identifier of the advertisable type record.',
                'example' => 3,
            ],
            'advertisable' => [
                'description' => 'Attributes for the underlying advertisable subtype. Optional when creating a draft.',
                'example' => [
                    'brand_id' => 12,
                    'model_id' => 30,
                    'year' => 2023,
                ],
            ],
            'slug' => [
                'description' => 'Unique slug for the ad. Auto-generated when omitted.',
                'example' => 'peugeot-206-2024',
            ],
            'title' => [
                'description' => 'Headline displayed for the ad. Defaults to a generic draft title when omitted.',
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
                'description' => 'Lifecycle status for moderation. Defaults to draft when omitted.',
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
            'comment_enable' => [
                'description' => 'Whether comments are allowed on the ad.',
                'example' => true,
            ],
            'phone_enable' => [
                'description' => 'Whether the phone contact option is enabled.',
                'example' => true,
            ],
            'chat_enable' => [
                'description' => 'Whether in-app chat is enabled for the ad.',
                'example' => true,
            ],
            'extra_amount' => [
                'description' => 'Additional amount expected on top of the exchanged item when swaps are accepted.',
                'example' => 150000,
            ],
            'exchange_description' => [
                'description' => 'Details of items that would be accepted in exchange.',
                'example' => 'Willing to swap for a newer model laptop plus cash.',
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
            'categories' => [
                'description' => 'Optional array of category assignments. Can be provided later via the ad update endpoint.',
                'example' => [
                    ['id' => 7, 'is_primary' => true],
                ],
            ],
            'attribute_values' => [
                'description' => 'Optional collection of attribute values linked to the ad. Supply only when these values are known.',
                'example' => [
                    ['definition_id' => 5, 'value_string' => 'Automatic'],
                    ['definition_id' => 12, 'value_integer' => 2024],
                ],
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
