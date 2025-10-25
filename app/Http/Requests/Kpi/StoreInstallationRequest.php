<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'installed_at' => ['nullable', 'date'],
            'app_version' => ['required', 'string', 'max:50'],
            'platform' => ['required', 'string', 'max:50'],
            'os_version' => ['nullable', 'string', 'max:100'],
            'device_model' => ['nullable', 'string', 'max:150'],
            'device_manufacturer' => ['nullable', 'string', 'max:150'],
            'locale' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:60'],
            'push_token' => ['nullable', 'string', 'max:255'],
            'install_source' => ['nullable', 'string', 'max:100'],
            'campaign' => ['nullable', 'string', 'max:100'],
            'is_reinstall' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
            'extra' => ['nullable', 'array'],
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
                'description' => 'Unique identifier for the device that installed the app.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'installed_at' => [
                'description' => 'Timestamp when the installation occurred.',
                'example' => '2024-04-01T09:15:00Z',
            ],
            'app_version' => [
                'description' => 'Version of the app that was installed.',
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
            'device_model' => [
                'description' => 'Marketing name of the device model.',
                'example' => 'Pixel 8',
            ],
            'device_manufacturer' => [
                'description' => 'Hardware manufacturer of the device.',
                'example' => 'Google',
            ],
            'locale' => [
                'description' => 'Locale configured on the device.',
                'example' => 'en-GB',
            ],
            'timezone' => [
                'description' => 'Time zone reported by the device.',
                'example' => 'Europe/London',
            ],
            'push_token' => [
                'description' => 'Token to send push notifications to the device.',
                'example' => 'b9df77f8faae4b079c6dce20472f923d',
            ],
            'install_source' => [
                'description' => 'Identifier for the store or campaign that initiated the install.',
                'example' => 'google-play',
            ],
            'campaign' => [
                'description' => 'Marketing campaign identifier associated with the install.',
                'example' => 'spring_sale_2024',
            ],
            'is_reinstall' => [
                'description' => 'Whether the install is a reinstall event.',
                'example' => false,
            ],
            'metadata' => [
                'description' => 'Structured metadata recorded for the install.',
                'example' => [
                    'referrer' => 'newsletter',
                ],
            ],
            'extra' => [
                'description' => 'Additional data sent by the client.',
                'example' => [
                    'sdk_version' => '1.4.0',
                ],
            ],
            'user_id' => [
                'description' => 'Identifier of the authenticated user associated with the device, when available.',
                'example' => 42,
            ],
        ];
    }
}
