<?php

namespace Modules\Ad\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ad\Http\Requests\Conversation\Concerns\HasMessagePayload;
use Modules\Ad\Http\Requests\Conversation\Contracts\ConversationMessageRequest;
use Modules\Ad\Models\AdConversation;

class StoreAdMessageRequest extends FormRequest implements ConversationMessageRequest
{
    use HasMessagePayload;

    public function authorize(): bool
    {
        $user = $this->user();
        $conversation = $this->route('conversation');

        if ($user === null || !$conversation instanceof AdConversation) {
            return false;
        }

        return $conversation->isParticipant($user);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return $this->messageValidationRules();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'message_type' => [
                'description' => 'Type of the message payload. One of text, image, audio, video, or location. Defaults to text.',
                'example' => 'text',
            ],
            'message' => [
                'description' => 'Message body to send to the conversation participant. Required when message_type is text.',
                'example' => 'Can you share more photos of the product?',
            ],
            'attachment' => [
                'description' => 'Binary attachment when sending image, audio, or video messages.',
            ],
            'location' => [
                'description' => 'Latitude and longitude payload when sending a location message.',
                'example' => ['latitude' => 40.7128, 'longitude' => -74.0060],
            ],
        ];
    }
}
