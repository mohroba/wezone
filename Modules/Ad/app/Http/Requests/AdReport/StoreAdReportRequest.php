<?php

namespace Modules\Ad\Http\Requests\AdReport;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ad_id' => ['required', 'integer', 'exists:ads,id'],
            'reason_code' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
