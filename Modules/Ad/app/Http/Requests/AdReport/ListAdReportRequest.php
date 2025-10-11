<?php

namespace Modules\Ad\Http\Requests\AdReport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAdReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in(['pending', 'in_review', 'resolved', 'dismissed'])],
            'ad_id' => ['nullable', 'integer', 'exists:ads,id'],
            'reported_by' => ['nullable', 'integer', 'exists:users,id'],
            'handled_by' => ['nullable', 'integer', 'exists:users,id'],
            'reason_code' => ['nullable', 'string', 'max:255'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'search' => ['nullable', 'string', 'max:500'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'without_pagination' => ['nullable', 'boolean'],
        ];
    }
}
