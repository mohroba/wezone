<?php

declare(strict_types=1);

namespace Modules\Ad\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('api') !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'body' => [
                'description' => 'Plain text content of the comment.',
                'example' => 'Is this item still available?',
            ],
        ];
    }
}
