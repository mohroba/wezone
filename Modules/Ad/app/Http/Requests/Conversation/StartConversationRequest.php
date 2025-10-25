<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class StartConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('api') !== null;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'message' => [
                'description' => 'Initial message to send to the ad owner.',
                'example' => 'Hi! I am interested in this item.',
            ],
        ];
    }
}
