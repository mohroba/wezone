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

    public function bodyParameters(): array
    {
        return [];
    }

    public function queryParameters(): array
    {
        return [
            'status' => [
                'description' => 'Only include payments matching this status (pending, paid, failed, etc.).',
                'example' => 'paid',
            ],
            'gateway' => [
                'description' => 'Only include payments processed by this gateway key.',
                'example' => 'payping',
            ],
            'ad_id' => [
                'description' => 'Limit payments to the given ad identifier.',
                'example' => 42,
            ],
            'purchase_id' => [
                'description' => 'Limit payments to the specified ad plan purchase.',
                'example' => 15,
            ],
            'per_page' => [
                'description' => 'Number of payments per page (1-100).',
                'example' => 15,
            ],
        ];
    }
}
