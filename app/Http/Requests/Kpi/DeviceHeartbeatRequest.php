<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class DeviceHeartbeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'last_seen_at' => ['nullable', 'date'],
            'last_heartbeat_at' => ['nullable', 'date'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'max:50'],
            'os_version' => ['nullable', 'string', 'max:100'],
            'extra' => ['nullable', 'array'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'device_uuid' => [
                'description' => 'Unique identifier for the device sending the heartbeat.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'last_seen_at' => [
                'description' => 'Timestamp when the device last interacted with the platform.',
                'example' => '2024-04-03T11:59:00Z',
            ],
            'last_heartbeat_at' => [
                'description' => 'Timestamp for the most recent heartbeat event.',
                'example' => '2024-04-03T12:00:00Z',
            ],
            'app_version' => [
                'description' => 'Current application version running on the device.',
                'example' => '2.3.1',
            ],
            'platform' => [
                'description' => 'Operating system of the device.',
                'example' => 'android',
            ],
            'os_version' => [
                'description' => 'Version string of the device operating system.',
                'example' => '14',
            ],
            'extra' => [
                'description' => 'Additional metadata provided by the client.',
                'example' => [
                    'battery_level' => 82,
                    'network' => 'wifi',
                ],
            ],
        ];
    }
}
