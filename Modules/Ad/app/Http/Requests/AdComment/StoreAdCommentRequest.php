<?php

namespace Modules\Ad\Http\Requests\AdComment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\Ad;
use Modules\Ad\Models\AdComment;

class StoreAdCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('api') !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:2000'],
            'parent_id' => ['nullable', 'integer', Rule::exists('ad_comments', 'id')],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'body' => [
                'description' => 'Content of the comment to attach to the ad.',
                'example' => 'Is the price negotiable?',
            ],
            'parent_id' => [
                'description' => 'Identifier of the parent comment when replying to a thread.',
                'example' => 87,
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $body = $this->input('body');

        if (is_string($body)) {
            $this->merge([
                'body' => trim($body),
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function () use ($validator): void {
            $parentId = $this->input('parent_id');

            if ($parentId === null) {
                return;
            }

            /** @var Ad|null $ad */
            $ad = $this->route('ad');

            if (! $ad instanceof Ad) {
                return;
            }

            $parentExistsInThread = AdComment::query()
                ->whereKey($parentId)
                ->where('ad_id', $ad->getKey())
                ->exists();

            if (! $parentExistsInThread) {
                $validator->errors()->add('parent_id', 'The selected parent comment does not belong to this ad.');
            }
        });
    }
}
