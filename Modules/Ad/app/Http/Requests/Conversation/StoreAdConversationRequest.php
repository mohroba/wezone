<?php

namespace Modules\Ad\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ad\Http\Requests\Conversation\Concerns\HasMessagePayload;
use Modules\Ad\Http\Requests\Conversation\Contracts\ConversationMessageRequest;
use Modules\Ad\Models\Ad;

class StoreAdConversationRequest extends FormRequest implements ConversationMessageRequest
{
    use HasMessagePayload;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return array_merge([
            'recipient_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ], $this->messageValidationRules());
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
            'message_type' => [
                'description' => 'Type of the initial message payload. One of text, image, audio, video, or location. Defaults to text.',
                'example' => 'text',
            ],
            'message' => [
                'description' => 'Initial message to send to the conversation participant. Required when message_type is text.',
                'example' => 'Hi! Is this item still available?',
            ],
            'attachment' => [
                'description' => 'Binary attachment when sending image, audio, or video messages.',
            ],
            'location' => [
                'description' => 'Latitude and longitude payload when sending a location message.',
                'example' => ['latitude' => 51.5072, 'longitude' => -0.1276],
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
