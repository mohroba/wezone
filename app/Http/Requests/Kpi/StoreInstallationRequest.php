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
}
