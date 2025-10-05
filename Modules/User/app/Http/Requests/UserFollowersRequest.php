<?php
namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFollowersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'followed_from' => ['nullable', 'date'],
            'followed_to'   => ['nullable', 'date', 'after_or_equal:followed_from'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    // Scribe v5.x: mark these as QUERY params & give examples for the tester
    public function queryParameters(): array
    {
        return [
            'followed_from' => [
                'description' => 'Start date (inclusive) for follow records (YYYY-MM-DD).',
                'example'     => '2025-01-01',
            ],
            'followed_to' => [
                'description' => 'End date (inclusive). Must be >= followed_from.',
                'example'     => '2025-12-31',
            ],
            'per_page' => [
                'description' => 'Items per page (1–100).',
                'example'     => 25,
            ],
        ];
    }
}
