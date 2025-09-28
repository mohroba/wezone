<?php

namespace Modules\Settings\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\Settings\Http\Requests\UpdateSettingsRequest;
use Modules\Settings\Services\SettingService;

class AdminSettingController extends Controller
{
    public function __construct(private readonly SettingService $settings)
    {
    }

    public function index(): JsonResponse
    {
        $keys = $this->settings->allowedKeys();
        $values = $this->settings->getSettings($keys);

        return ApiResponse::success(
            'Settings retrieved successfully.',
            [
                'settings' => $this->settings->present($keys, $values),
            ]
        );
    }

    public function store(UpdateSettingsRequest $request): JsonResponse
    {
        $this->settings->updateSettings($request->settings());

        $keys = $this->settings->allowedKeys();
        $values = $this->settings->getSettings($keys);

        return ApiResponse::success(
            'Settings updated successfully.',
            [
                'settings' => $this->settings->present($keys, $values),
            ]
        );
    }
}
