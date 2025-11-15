<?php

namespace Modules\Ad\Http\Requests\Conversation\Contracts;

use Illuminate\Http\UploadedFile;

interface ConversationMessageRequest
{
    public function messageType(): string;

    public function messageBody(): ?string;

    public function uploadedAttachment(): ?UploadedFile;

    /**
     * @return array{latitude: float, longitude: float}|null
     */
    public function locationPayload(): ?array;
}
