<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ended_at' => ['nullable', 'date'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'max:50'],
            'os_version' => ['nullable', 'string', 'max:100'],
            'network_type' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:150'],
            'country' => ['nullable', 'string', 'max:150'],
            'metadata' => ['nullable', 'array'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'ended_at' => [
                'description' => 'Timestamp when the session concluded.',
                'example' => '2024-04-05T10:25:00Z',
            ],
            'duration_seconds' => [
                'description' => 'Updated total duration of the session in seconds.',
                'example' => 1520,
            ],
            'app_version' => [
                'description' => 'Updated app version associated with the session.',
                'example' => '2.3.2',
            ],
            'platform' => [
                'description' => 'Operating system of the device.',
                'example' => 'android',
            ],
            'os_version' => [
                'description' => 'Updated operating system version.',
                'example' => '14',
            ],
            'network_type' => [
                'description' => 'Network connection type detected at the end of the session.',
                'example' => '4g',
            ],
            'city' => [
                'description' => 'Updated city for the session, if applicable.',
                'example' => 'New York',
            ],
            'country' => [
                'description' => 'Updated country for the session, if applicable.',
                'example' => 'United States',
            ],
            'metadata' => [
                'description' => 'Additional metadata merged into the session record.',
                'example' => [
                    'last_screen' => 'checkout',
                ],
            ],
            'user_id' => [
                'description' => 'Identifier of the authenticated user associated with the session.',
                'example' => 42,
            ],
        ];
    }
}
