<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class StoreUninstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'uninstalled_at' => ['nullable', 'date'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'max:50'],
            'reason' => ['nullable', 'string', 'max:255'],
            'report_source' => ['nullable', 'string', 'max:100'],
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
                'description' => 'Unique identifier for the device that uninstalled the app.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'uninstalled_at' => [
                'description' => 'Timestamp when the uninstall event occurred.',
                'example' => '2024-04-05T17:20:00Z',
            ],
            'app_version' => [
                'description' => 'Application version that was uninstalled.',
                'example' => '2.3.1',
            ],
            'platform' => [
                'description' => 'Operating system of the device.',
                'example' => 'ios',
            ],
            'reason' => [
                'description' => 'Optional reason provided for the uninstall.',
                'example' => 'User opted out during offboarding survey.',
            ],
            'report_source' => [
                'description' => 'Source that reported the uninstall event.',
                'example' => 'app_store',
            ],
            'metadata' => [
                'description' => 'Structured metadata for additional analytics.',
                'example' => [
                    'previous_sessions' => 12,
                ],
            ],
            'user_id' => [
                'description' => 'Identifier of the authenticated user, if available.',
                'example' => 42,
            ],
        ];
    }
}
