<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListPaymentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', 'max:50'],
            'gateway' => ['nullable', 'string', 'max:100'],
            'ad_id' => ['nullable', 'integer', 'exists:ads,id'],
            'purchase_id' => ['nullable', 'integer', 'exists:ad_plan_purchases,id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
