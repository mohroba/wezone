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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'status' => [
                'description' => 'Filter reports by their current status.',
                'example' => 'pending',
            ],
            'ad_id' => [
                'description' => 'Limit results to reports targeting a specific ad.',
                'example' => 512,
            ],
            'reported_by' => [
                'description' => 'Filter by the identifier of the reporting user.',
                'example' => 33,
            ],
            'handled_by' => [
                'description' => 'Filter by the identifier of the moderator handling the report.',
                'example' => 7,
            ],
            'reason_code' => [
                'description' => 'Filter by the report reason code.',
                'example' => 'spam',
            ],
            'from' => [
                'description' => 'Limit reports to those created on or after this ISO 8601 date.',
                'example' => '2024-04-01',
            ],
            'to' => [
                'description' => 'Limit reports to those created on or before this ISO 8601 date.',
                'example' => '2024-04-30',
            ],
            'search' => [
                'description' => 'Free-text search across report content and related ad data.',
                'example' => 'duplicate listing',
            ],
            'per_page' => [
                'description' => 'Number of results to return per page when paginating.',
                'example' => 25,
            ],
            'without_pagination' => [
                'description' => 'When true, returns all results without pagination.',
                'example' => false,
            ],
        ];
    }
}
