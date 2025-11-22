<?php

namespace Modules\Monetization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway' => ['required', 'string'],
            'payload' => ['required', 'array'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'gateway' => [
                'description' => 'Payment gateway key associated with the callback.',
                'example' => 'payping',
                'required' => true,
            ],
            'payload' => [
                'description' => 'Raw callback payload forwarded from the gateway.',
                'example' => ['status' => 'paid'],
                'required' => true,
            ],
        ];
    }
}
