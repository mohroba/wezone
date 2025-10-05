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
}
