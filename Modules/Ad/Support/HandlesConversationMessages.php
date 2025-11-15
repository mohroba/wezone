<?php

namespace Modules\Ad\Support;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Modules\Ad\Http\Requests\Conversation\Contracts\ConversationMessageRequest;
use Modules\Ad\Models\AdConversation;
use Modules\Ad\Models\AdMessage;
use Throwable;

trait HandlesConversationMessages
{
    protected function createConversationMessage(
        AdConversation $conversation,
        ConversationMessageRequest $request,
        User $sender
    ): AdMessage {
        $attachmentDescriptor = null;

        try {
            $attachmentDescriptor = $this->storeAttachmentIfNeeded($conversation, $request);

            $attributes = [
                'user_id' => $sender->getKey(),
                'body' => $request->messageType() === 'text'
                    ? (string) $request->messageBody()
                    : '',
                'type' => $request->messageType(),
                'payload' => $this->buildPayload($request, $attachmentDescriptor),
            ];

            /** @var AdMessage $message */
            $message = $conversation->messages()->create($attributes);

            return $message->load('sender:id,username');
        } catch (Throwable $exception) {
            $this->deleteStoredAttachment($attachmentDescriptor);

            throw $exception;
        }
    }

    protected function guardAgainstBlockedParticipants(User $sender, iterable $participants): void
    {
        foreach ($participants as $participant) {
            if (!$participant instanceof User || $participant->is($sender)) {
                continue;
            }

            if ($sender->hasBlocked($participant) || $sender->isBlockedBy($participant)) {
                throw new AuthorizationException(__('You cannot send messages to this conversation.'));
            }
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function storeAttachmentIfNeeded(
        AdConversation $conversation,
        ConversationMessageRequest $request
    ): ?array {
        if (!in_array($request->messageType(), ['image', 'audio', 'video'], true)) {
            return null;
        }

        $file = $request->uploadedAttachment();

        if (!$file instanceof UploadedFile) {
            return null;
        }

        $disk = $this->resolveAttachmentDisk();
        $path = $file->store("ad_messages/{$conversation->getKey()}", $disk);

        return [
            'disk' => $disk,
            'path' => $path,
            'mime_type' => $file->getClientMimeType() ?? $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    /**
     * @param array<string, mixed>|null $attachmentDescriptor
     */
    private function buildPayload(
        ConversationMessageRequest $request,
        ?array $attachmentDescriptor
    ): ?array {
        return match ($request->messageType()) {
            'location' => $request->locationPayload(),
            'image', 'audio', 'video' => $attachmentDescriptor,
            default => null,
        };
    }

    private function resolveAttachmentDisk(): string
    {
        $disks = config('filesystems.disks', []);

        if (array_key_exists('public', $disks)) {
            return 'public';
        }

        $default = config('filesystems.default', 'local');

        if (array_key_exists($default, $disks)) {
            return $default;
        }

        return 'local';
    }

    /**
     * @param array<string, mixed>|null $attachmentDescriptor
     */
    private function deleteStoredAttachment(?array $attachmentDescriptor): void
    {
        if (!is_array($attachmentDescriptor)) {
            return;
        }

        $disk = Arr::get($attachmentDescriptor, 'disk');
        $path = Arr::get($attachmentDescriptor, 'path');

        if (!$disk || !$path) {
            return;
        }

        $storage = Storage::disk($disk);

        if ($storage->exists($path)) {
            $storage->delete($path);
        }
    }
}
