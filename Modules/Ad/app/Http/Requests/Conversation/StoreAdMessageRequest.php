<?php

namespace Modules\Ad\Http\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ad\Models\AdConversation;

class StoreAdMessageRequest extends FormRequest
{
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
        return [
            'message' => ['required', 'string', 'max:2000'],
        ];
    }
}
