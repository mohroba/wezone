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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'device_uuid' => [
                'description' => 'Unique identifier for the device emitting the events.',
                'example' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'session_uuid' => [
                'description' => 'Identifier of the session that captured the events.',
                'example' => '3f2504e0-4f89-11d3-9a0c-0305e82c3301',
            ],
            'platform' => [
                'description' => 'Operating system of the device.',
                'example' => 'android',
            ],
            'user_id' => [
                'description' => 'Identifier of the authenticated user emitting the events.',
                'example' => 42,
            ],
            'events' => [
                'description' => 'Collection of analytics events to record.',
                'example' => [
                    [
                        'event_uuid' => 'd94f0ab4-9fc9-4e36-8d92-3d1b60fa7f30',
                        'event_key' => 'search_performed',
                        'event_name' => 'Search Performed',
                        'event_category' => 'engagement',
                        'event_value' => 1,
                        'occurred_at' => '2024-04-05T10:05:00Z',
                        'metadata' => [
                            'query' => 'wireless headphones',
                            'results_count' => 12,
                        ],
                    ],
                ],
            ],
            'events.*.event_uuid' => [
                'description' => 'Client-generated identifier for the individual event.',
                'example' => 'd94f0ab4-9fc9-4e36-8d92-3d1b60fa7f30',
            ],
            'events.*.event_key' => [
                'description' => 'Key representing the type of event.',
                'example' => 'search_performed',
            ],
            'events.*.event_name' => [
                'description' => 'Human-readable name of the event.',
                'example' => 'Search Performed',
            ],
            'events.*.event_category' => [
                'description' => 'Category grouping for the event.',
                'example' => 'engagement',
            ],
            'events.*.event_value' => [
                'description' => 'Numeric value associated with the event.',
                'example' => 1,
            ],
            'events.*.occurred_at' => [
                'description' => 'Timestamp indicating when the event happened.',
                'example' => '2024-04-05T10:05:00Z',
            ],
            'events.*.metadata' => [
                'description' => 'Arbitrary metadata for the event payload.',
                'example' => [
                    'query' => 'wireless headphones',
                    'results_count' => 12,
                ],
            ],
        ];
    }
}
