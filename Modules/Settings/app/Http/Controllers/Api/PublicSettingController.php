<?php

namespace Modules\Settings\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\Settings\Models\Setting;
use Modules\Settings\Services\SettingService;
use Symfony\Component\HttpFoundation\Response;

class PublicSettingController extends Controller
{
    public function __construct(private readonly SettingService $settings)
    {
    }

    public function index(): JsonResponse
    {
        $keys = $this->settings->publicKeys();
        $values = $this->settings->getSettings($keys);

        return ApiResponse::success(
            'Settings retrieved successfully.',
            [
                'settings' => $this->settings->present($keys, $values),
            ]
        );
    }

    public function show(string $key): JsonResponse
    {
        if (! in_array($key, $this->settings->publicKeys(), true)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $setting = Setting::query()->firstWhere('key', $key);

        return ApiResponse::success(
            'Setting retrieved successfully.',
            [
                'setting' => [
                    'key' => $key,
                    'value' => $setting?->value,
                    'description' => config('settings.keys.' . $key . '.description'),
                    'is_public' => true,
                ],
            ]
        );
    }
}
