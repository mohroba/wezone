<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BlockUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $authUser = $this->user();
            $targetUser = $this->route('user');

            if ($authUser !== null && $targetUser !== null && $authUser->is($targetUser)) {
                $validator->errors()->add('user', __('You cannot block yourself.'));
            }
        });
    }

    public function bodyParameters(): array
    {
        return [];
    }
}
