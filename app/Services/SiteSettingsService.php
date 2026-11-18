<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    public function get(?string $key = null, mixed $default = null): mixed
    {
        $settings = Cache::remember(config('site-settings.cache_key'), config('site-settings.cache_duration'), function () {
            return SiteSetting::query()->pluck('value', 'key')->all();
        });

        if ($key === null) {
            return $settings;
        }

        return $settings[$key] ?? $default ?? config("site-settings.defaults.{$key}");
    }

    public function set(string $key, string $value): SiteSetting
    {
        $setting = SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        $this->clear();

        return $setting;
    }

    public function clear()
    {
        Cache::forget(config('site-settings.cache_key'));
    }
}