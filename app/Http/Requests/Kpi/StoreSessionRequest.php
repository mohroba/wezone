<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'session_uuid' => ['required', 'uuid'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'app_version' => ['required', 'string', 'max:50'],
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
            'device_uuid' => [
                'description' => 'Unique identifier for the device that generated the session.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'session_uuid' => [
                'description' => 'Unique identifier for the tracked session.',
                'example' => '3f2504e0-4f89-11d3-9a0c-0305e82c3301',
            ],
            'started_at' => [
                'description' => 'Timestamp indicating when the session started.',
                'example' => '2024-04-05T10:00:00Z',
            ],
            'ended_at' => [
                'description' => 'Timestamp indicating when the session ended, if finished.',
                'example' => '2024-04-05T10:25:00Z',
            ],
            'duration_seconds' => [
                'description' => 'Total session duration in seconds, when already computed.',
                'example' => 1500,
            ],
            'app_version' => [
                'description' => 'Version of the app used during the session.',
                'example' => '2.3.1',
            ],
            'platform' => [
                'description' => 'Operating system of the device.',
                'example' => 'android',
            ],
            'os_version' => [
                'description' => 'Version of the operating system.',
                'example' => '14',
            ],
            'network_type' => [
                'description' => 'Network connection type observed during the session.',
                'example' => 'wifi',
            ],
            'city' => [
                'description' => 'City resolved for the session location, if available.',
                'example' => 'San Francisco',
            ],
            'country' => [
                'description' => 'Country resolved for the session location, if available.',
                'example' => 'United States',
            ],
            'metadata' => [
                'description' => 'Additional metadata captured for the session.',
                'example' => [
                    'screen_count' => 5,
                ],
            ],
            'user_id' => [
                'description' => 'Identifier of the authenticated user linked to the session.',
                'example' => 42,
            ],
        ];
    }
}
