<?php

namespace App\Support;

use App\Models\SiteSetting;
use App\Services\SiteSettingsService;

class SiteConfig
{
    public static function get(string $key): mixed
    {
        return app(SiteSettingsService::class)->get($key);
    }

    public static function store(string $key, string $value): SiteSetting
    {
        return app(SiteSettingsService::class)->set($key, $value);
    }
}
