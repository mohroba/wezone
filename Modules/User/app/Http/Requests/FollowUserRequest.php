<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class FollowUserRequest extends FormRequest
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

            if ($authUser === null || $targetUser === null) {
                return;
            }

            if ($authUser->is($targetUser)) {
                $validator->errors()->add('user', __('You cannot follow yourself.'));
            }

            if ($authUser->hasBlocked($targetUser)) {
                $validator->errors()->add('user', __('You cannot follow a user you have blocked.'));
            }

            if ($targetUser->hasBlocked($authUser)) {
                $validator->errors()->add('user', __('You cannot follow a user who has blocked you.'));
            }
        });
    }

    public function bodyParameters(): array
    {
        // This endpoint has no request body.
        return [];
    }
}
