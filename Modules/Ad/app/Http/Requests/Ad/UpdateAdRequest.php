<?php

namespace Modules\Ad\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Ad\Http\Requests\Concerns\ValidatesAttributeValueData;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdvertisableType as AdvertisableTypeModel;
use Modules\Ad\Services\Advertisable\AdvertisablePayloadValidator;
use Modules\Ad\Support\AdvertisableType as AdvertisableTypeSupport;

class UpdateAdRequest extends FormRequest
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
        $table = (new Ad())->getTable();
        $routeParam = $this->route('ad');
        $adId = $routeParam instanceof Ad
            ? $routeParam->getKey()
            : (is_numeric($routeParam) ? (int) $routeParam : null);

        $slugUnique = Rule::unique($table, 'slug');
        if ($adId !== null) {
            $slugUnique->ignore($adId);
        }

        $statusValues = ['draft', 'pending_review', 'published', 'rejected', 'archived', 'expired'];

        return [
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'advertisable_type_id' => ['required', 'integer', 'exists:advertisable_types,id'],
            'advertisable' => ['required', 'array'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash', $slugUnique],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', Rule::in($statusValues)],
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
            'categories' => ['required', 'array'],
            'categories.*.id' => ['required', 'integer', 'exists:ad_categories,id'],
            'categories.*.is_primary' => ['nullable', 'boolean'],
            'categories.*.assigned_by' => ['nullable', 'integer', 'exists:users,id'],
            'attribute_values' => ['required', 'array'],
            'attribute_values.*.definition_id' => ['required', 'integer', 'exists:ad_attribute_definitions,id'],
            'attribute_values.*.value_string' => ['nullable', 'string'],
            'attribute_values.*.value_integer' => ['nullable', 'integer'],
            'attribute_values.*.value_decimal' => ['nullable', 'numeric'],
            'attribute_values.*.value_boolean' => ['nullable', 'boolean'],
            'attribute_values.*.value_date' => ['nullable', 'date'],
            'attribute_values.*.value_json' => ['nullable', 'array'],
            'status_note' => ['nullable', 'string'],
            'status_metadata' => ['nullable', 'array'],
        ];
    }

    public function advertisablePayload(): ?array
    {
        if ($this->advertisablePayload !== null) {
            return $this->advertisablePayload;
        }

        $validated = $this->validated();

        return Arr::get($validated, 'advertisable');
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

            if (is_array($attributeValues)) {
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
                'description' => 'Identifier of the ad owner.',
                'example' => 42,
                'required' => false,
            ],
            'advertisable_type_id' => [
                'description' => 'Identifier of the advertisable type record.',
                'example' => 3,
                'required' => true,
            ],
            'advertisable' => [
                'description' => 'Attributes for the underlying advertisable subtype.',
                'example' => [
                    'brand_id' => 12,
                    'model_id' => 30,
                    'year' => 2023,
                ],
                'required' => true,
            ],
            'slug' => [
                'description' => 'Unique slug for the ad.',
                'example' => 'peugeot-206-2024',
            ],
            'title' => [
                'description' => 'Headline displayed for the ad.',
                'example' => 'Peugeot 206 2024',
            ],
            'categories' => [
                'description' => 'Array of category assignments.',
                'example' => [
                    ['id' => 7, 'is_primary' => true],
                ],
                'required' => true,
            ],
            'attribute_values' => [
                'description' => 'Collection of attribute values linked to the ad.',
                'example' => [
                    ['definition_id' => 5, 'value_string' => 'Automatic'],
                    ['definition_id' => 12, 'value_integer' => 2024],
                ],
                'required' => true,
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
