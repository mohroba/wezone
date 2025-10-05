<?php

namespace App\Http\Requests\Kpi;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uuid' => ['required', 'uuid'],
            'session_uuid' => ['nullable', 'uuid'],
            'platform' => ['nullable', 'string', 'max:50'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'events' => ['required', 'array', 'min:1'],
            'events.*.event_uuid' => ['nullable', 'uuid'],
            'events.*.event_key' => ['required', 'string', 'max:100'],
            'events.*.event_name' => ['nullable', 'string', 'max:255'],
            'events.*.event_category' => ['nullable', 'string', 'max:100'],
            'events.*.event_value' => ['nullable', 'numeric'],
            'events.*.occurred_at' => ['nullable', 'date'],
            'events.*.metadata' => ['nullable', 'array'],
        ];
    }
}
