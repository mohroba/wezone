<?php

namespace Modules\Ad\Http\Requests\Conversation\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

trait HasMessagePayload
{
    protected static function allowedMessageTypes(): array
    {
        return ['text', 'image', 'audio', 'video', 'location'];
    }

    protected function prepareForValidation(): void
    {
        $type = $this->input('message_type');

        if (is_string($type)) {
            $type = Str::of($type)->trim()->lower()->toString();
        }

        if (empty($type)) {
            $type = 'text';
        }

        $this->merge([
            'message_type' => $type,
        ]);

        if ($this->has('location') && !is_array($this->input('location'))) {
            $this->offsetUnset('location');
        }
    }

    protected function messageValidationRules(): array
    {
        $attachmentRules = [
            'prohibited_unless:message_type,image,audio,video',
            Rule::requiredIf(fn () => $this->expectsAttachmentUpload()),
        ];

        if ($this->expectsAttachmentUpload()) {
            $attachmentRules = array_merge($attachmentRules, $this->attachmentValidationRules());
        } else {
            $attachmentRules[] = 'nullable';
        }

        return [
            'message_type' => ['required', 'string', Rule::in(self::allowedMessageTypes())],
            'message' => ['required_if:message_type,text', 'string', 'max:2000'],
            'attachment' => $attachmentRules,
            'location' => [
                'prohibited_unless:message_type,location',
                Rule::requiredIf(fn () => $this->messageType() === 'location'),
                'array',
            ],
            'location.latitude' => [
                Rule::requiredIf(fn () => $this->messageType() === 'location'),
                'numeric',
                'between:-90,90',
            ],
            'location.longitude' => [
                Rule::requiredIf(fn () => $this->messageType() === 'location'),
                'numeric',
                'between:-180,180',
            ],
        ];
    }

    public function messageType(): string
    {
        return $this->input('message_type', 'text');
    }

    public function messageBody(): ?string
    {
        if ($this->messageType() !== 'text') {
            return null;
        }

        return $this->string('message')->trim()->toString();
    }

    public function uploadedAttachment(): ?UploadedFile
    {
        /** @var UploadedFile|null $file */
        $file = $this->file('attachment');

        return $file;
    }

    public function locationPayload(): ?array
    {
        if ($this->messageType() !== 'location') {
            return null;
        }

        return [
            'latitude' => (float) $this->input('location.latitude'),
            'longitude' => (float) $this->input('location.longitude'),
        ];
    }

    public function expectsAttachmentUpload(): bool
    {
        return in_array($this->messageType(), ['image', 'audio', 'video'], true);
    }

    protected function attachmentValidationRules(): array
    {
        return match ($this->messageType()) {
            'image' => ['image', 'max:5120'],
            'audio' => ['file', 'mimetypes:audio/mpeg,audio/ogg,audio/wav,audio/mp4,audio/aac,audio/webm', 'max:20480'],
            'video' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/webm', 'max:51200'],
            default => ['nullable'],
        };
    }
}
