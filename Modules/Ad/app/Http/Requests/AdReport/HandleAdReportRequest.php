<?php

namespace Modules\Ad\Http\Requests\AdReport;

use Illuminate\Foundation\Http\FormRequest;

class HandleAdReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resolution_notes' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
