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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'ad_id' => [
                'description' => 'Identifier of the ad being reported.',
                'example' => 512,
            ],
            'reason_code' => [
                'description' => 'Machine-readable code representing the report reason.',
                'example' => 'spam',
            ],
            'description' => [
                'description' => 'Free-form explanation provided by the reporter.',
                'example' => 'This listing is duplicated multiple times.',
            ],
            'metadata' => [
                'description' => 'Additional context supplied with the report.',
                'example' => [
                    'screenshot_url' => 'https://example.com/evidence.png',
                ],
            ],
        ];
    }
}
