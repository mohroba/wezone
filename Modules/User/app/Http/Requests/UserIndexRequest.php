<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'follower_id' => ['nullable', 'integer', 'exists:users,id'],
            'email' => ['nullable', 'string'],
            'mobile' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function queryParameters(): array
    {
        return [
            'follower_id' => ['description' => 'Filter users followed by this user ID.', 'example' => 42],
            'email'       => ['description' => 'Filter by email (partial match allowed in your controller).', 'example' => 'jane@example.com'],
            'mobile'      => ['description' => 'Filter by mobile number.', 'example' => '09123456789'],
            'username'    => ['description' => 'Filter by username.', 'example' => 'jane_doe'],
            'per_page'    => ['description' => 'Items per page (1–100).', 'example' => 20],
        ];
    }

}
