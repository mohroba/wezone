<?php

namespace Modules\Settings\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Settings\Models\Setting;

class SettingService
{
    public function allowedKeys(): array
    {
        return array_keys(config('settings.keys', []));
    }

    public function publicKeys(): array
    {
        return array_keys(array_filter(config('settings.keys', []), static function (array $meta): bool {
            return $meta['public'] ?? false;
        }));
    }

    public function getSettings(?array $keys = null): Collection
    {
        $query = Setting::query();

        if (! empty($keys)) {
            $query->forKeys($keys);
        }

        $settings = $query->get()->pluck('value', 'key');

        if ($keys !== null) {
            $settings = collect($keys)->mapWithKeys(static function (string $key) use ($settings) {
                return [$key => $settings->get($key)];
            });
        }

        return $settings;
    }

    public function updateSettings(array $values): Collection
    {
        $values = Arr::only($values, $this->allowedKeys());

        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return $this->getSettings(array_keys($values));
    }

    public function present(array $keys, Collection $values): array
    {
        return collect($keys)->map(function (string $key) use ($values) {
            $meta = config('settings.keys.' . $key, []);

            return [
                'key' => $key,
                'value' => $values->get($key),
                'description' => $meta['description'] ?? null,
                'is_public' => (bool) ($meta['public'] ?? false),
            ];
        })->values()->toArray();
    }
}
