<?php

namespace Modules\Ad\Http\Requests\AdvertisableType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Support\AdvertisableType as AdvertisableTypeSupport;

class StoreAdvertisableTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table = (new AdvertisableType())->getTable();

        return [
            'key' => ['required', 'string', 'max:64', 'alpha_dash', Rule::unique($table, 'key')],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'model_class' => ['required', 'string', 'max:255', Rule::in(AdvertisableTypeSupport::allowed())],
            'icon' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,gif',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif',
                'max:5120',
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'key' => [
                'description' => 'Unique identifier for the advertisable type.',
                'example' => 'electronics',
            ],
            'label' => [
                'description' => 'Human readable label displayed to users.',
                'example' => 'Electronics',
            ],
            'description' => [
                'description' => 'Optional description for clients.',
                'example' => 'Listings that cover consumer electronics.',
            ],
            'model_class' => [
                'description' => 'Backed advertisable model class (must be supported).',
                'example' => '\\Modules\\Ad\\Models\\AdCar',
            ],
            'icon' => [
                'description' => 'Optional icon image representing the type (JPEG, PNG, WebP, or GIF; max 5 MB).',
                'type' => 'file',
            ],
        ];
    }
}
