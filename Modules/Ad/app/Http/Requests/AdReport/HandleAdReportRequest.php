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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'resolution_notes' => [
                'description' => 'Notes shared with the report to explain the resolution.',
                'example' => 'Listing was reviewed and found to comply with guidelines.',
            ],
            'metadata' => [
                'description' => 'Additional metadata recorded during report handling.',
                'example' => [
                    'action_taken' => 'monitored',
                ],
            ],
        ];
    }
}
