<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'platform' => ['required', 'string', 'max:50'],
            'app_version' => ['required', 'string', 'max:50'],
            'os_version' => ['nullable', 'string', 'max:100'],
            'device_model' => ['nullable', 'string', 'max:150'],
            'device_manufacturer' => ['nullable', 'string', 'max:150'],
            'locale' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'push_token' => ['nullable', 'string', 'max:255'],
            'first_seen_at' => ['nullable', 'date'],
            'last_seen_at' => ['nullable', 'date'],
            'last_heartbeat_at' => ['nullable', 'date'],
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
                'description' => 'Unique identifier for the device in UUID format.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'platform' => [
                'description' => 'The operating system running on the device.',
                'example' => 'ios',
            ],
            'app_version' => [
                'description' => 'Semantic version of the application installed on the device.',
                'example' => '2.3.1',
            ],
            'os_version' => [
                'description' => 'Version of the operating system.',
                'example' => '16.4.1',
            ],
            'device_model' => [
                'description' => 'Human readable model name of the device.',
                'example' => 'iPhone 14 Pro',
            ],
            'device_manufacturer' => [
                'description' => 'Manufacturer of the device hardware.',
                'example' => 'Apple',
            ],
            'locale' => [
                'description' => 'IETF language tag representing the device locale.',
                'example' => 'en-US',
            ],
            'timezone' => [
                'description' => 'IANA timezone identifier reported by the device.',
                'example' => 'America/New_York',
            ],
            'push_token' => [
                'description' => 'Token used for sending push notifications to the device.',
                'example' => 'd2f7aa31e7f846d3a2b99b9cb6a1ef01',
            ],
            'first_seen_at' => [
                'description' => 'Timestamp when the device was first seen.',
                'example' => '2024-04-01T10:30:00Z',
            ],
            'last_seen_at' => [
                'description' => 'Timestamp of the most recent device interaction.',
                'example' => '2024-04-03T12:15:00Z',
            ],
            'last_heartbeat_at' => [
                'description' => 'Timestamp of the latest heartbeat received from the device.',
                'example' => '2024-04-03T12:00:00Z',
            ],
            'extra' => [
                'description' => 'Additional key-value metadata captured during registration.',
                'example' => [
                    'carrier' => 'Verizon',
                    'build_number' => '20301',
                ],
            ],
        ];
    }
}
