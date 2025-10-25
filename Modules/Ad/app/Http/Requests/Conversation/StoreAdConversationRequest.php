<?php

namespace Modules\Ad\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ad\Models\Ad;

class StoreAdConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'recipient_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'recipient_id' => [
                'description' => 'Identifier of the user receiving the first message. Defaults to the ad owner when omitted.',
                'example' => 128,
            ],
            'message' => [
                'description' => 'Initial message to send to the conversation participant.',
                'example' => 'Hi! Is this item still available?',
            ],
        ];
    }

    public function recipientId(?Ad $ad = null): int
    {
        $recipientId = (int) $this->input('recipient_id');

        if ($recipientId > 0) {
            return $recipientId;
        }

        if ($ad !== null) {
            return (int) $ad->user_id;
        }

        return (int) $this->route('ad')?->user_id;
    }
}
