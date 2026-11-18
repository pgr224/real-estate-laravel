<?php

namespace Tests\Feature;

use App\Services\SiteSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_the_configured_default_when_a_setting_is_missing(): void
    {
        $this->assertSame('£', app(SiteSettingsService::class)->get('currency'));
    }

    public function test_it_stores_and_reads_site_settings(): void
    {
        $service = app(SiteSettingsService::class);
        $service->set('name', 'Local Realty');

        $this->assertSame('Local Realty', $service->get('name'));
    }
}
