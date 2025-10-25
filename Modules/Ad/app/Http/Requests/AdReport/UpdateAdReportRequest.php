<?php

namespace Modules\Ad\Http\Requests\AdReport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', 'string', Rule::in(['pending', 'in_review', 'resolved', 'dismissed'])],
            'resolution_notes' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'status' => [
                'description' => 'New status to assign to the report.',
                'example' => 'in_review',
            ],
            'resolution_notes' => [
                'description' => 'Notes explaining the actions taken on the report.',
                'example' => 'Contacted the seller for clarification.',
            ],
            'metadata' => [
                'description' => 'Supplementary metadata maintained by moderators.',
                'example' => [
                    'internal_ticket' => 'AD-204',
                ],
            ],
        ];
    }
}
