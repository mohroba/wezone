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
        /** @var Ad $ad */
        $ad = $this->route('ad');
        $statusValues = ['draft', 'pending_review', 'published', 'rejected', 'archived', 'expired'];

        return [
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'advertisable_type' => ['sometimes', 'required', 'string', Rule::in(AdvertisableType::allowed())],
            'advertisable_id' => ['sometimes', 'required', 'integer'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique($ad->getTable(), 'slug')->ignore($ad->id),
            ],
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
            'status_note' => ['nullable', 'string'],
            'status_metadata' => ['nullable', 'array'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_negotiable' => $this->toBoolean($this->input('is_negotiable')),
            'is_exchangeable' => $this->toBoolean($this->input('is_exchangeable')),
        ]);
    }

    public function withValidator($validator): void
    {
        /** @var Ad $ad */
        $ad = $this->route('ad');

        $validator->after(function ($validator) use ($ad): void {
            $type = $this->input('advertisable_type', $ad->advertisable_type);
            $advertisableId = $this->input('advertisable_id', $ad->advertisable_id);

            if (! AdvertisableType::isAllowed($type)) {
                return;
            }

            $table = AdvertisableType::tableFor($type);
            $exists = DB::table($table)->where('id', $advertisableId)->exists();

            if (! $exists) {
                $validator->errors()->add('advertisable_id', 'The selected advertisable does not exist.');
            }
        });
    }

    private function toBoolean(mixed $value): ?bool
    {
        if ($value === null) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }
}
