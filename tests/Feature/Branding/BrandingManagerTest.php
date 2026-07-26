<?php

namespace Tests\Feature\Branding;

use App\Core\Branding\BrandingManager;
use App\Core\Tenancy\Models\TenantSettings;
use App\Core\Tenancy\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesTenantMemberships;
use Tests\TestCase;

class BrandingManagerTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_loads_settings_from_current_tenant(): void
    {
        $tenant = $this->createTenant(['name' => 'Foundation Tenant A', 'slug' => 'foundation-a']);
        TenantSettings::query()->create([
            'tenant_id' => $tenant->getKey(),
            'locale' => 'es',
            'timezone' => 'America/Santiago',
            'currency' => 'CLP',
            'theme' => 'light',
            'primary_color' => '#111827',
            'secondary_color' => '#f97316',
            'logo_path' => 'tenants/foundation-a/logo.svg',
            'favicon_path' => 'tenants/foundation-a/favicon.ico',
            'date_format' => 'd-m-Y',
            'time_format' => 'H:i',
        ]);

        app(TenantContext::class)->set($tenant);

        $branding = app(BrandingManager::class);

        $this->assertSame('Foundation Tenant A', $branding->name());
        $this->assertSame('foundation-a', $branding->slug());
        $this->assertSame('#111827', $branding->primaryColor());
        $this->assertSame('#f97316', $branding->secondaryColor());
        $this->assertSame('tenants/foundation-a/logo.svg', $branding->logoPath());
        $this->assertSame('tenants/foundation-a/favicon.ico', $branding->faviconPath());
        $this->assertSame('light', $branding->theme());
        $this->assertSame('es', $branding->locale());
        $this->assertSame('America/Santiago', $branding->timezone());
        $this->assertSame('CLP', $branding->currency());
        $this->assertSame('d-m-Y', $branding->dateFormat());
        $this->assertSame('H:i', $branding->timeFormat());
    }

    public function test_different_tenants_receive_different_branding(): void
    {
        $firstTenant = $this->createTenant(['name' => 'First Tenant', 'slug' => 'first-tenant']);
        $secondTenant = $this->createTenant(['name' => 'Second Tenant', 'slug' => 'second-tenant']);
        TenantSettings::query()->create([
            'tenant_id' => $firstTenant->getKey(),
            'primary_color' => '#0f766e',
            'secondary_color' => '#facc15',
        ]);
        TenantSettings::query()->create([
            'tenant_id' => $secondTenant->getKey(),
            'primary_color' => '#7c3aed',
            'secondary_color' => '#06b6d4',
        ]);

        $context = app(TenantContext::class);
        $branding = app(BrandingManager::class);

        $context->set($firstTenant);
        $this->assertSame('#0f766e', $branding->primaryColor());
        $this->assertSame('#facc15', $branding->secondaryColor());

        $context->set($secondTenant);
        $branding->clear();

        $this->assertSame('#7c3aed', $branding->primaryColor());
        $this->assertSame('#06b6d4', $branding->secondaryColor());
    }

    public function test_missing_settings_fail_gracefully(): void
    {
        $tenant = $this->createTenant(['name' => 'Tenant Without Settings', 'slug' => 'without-settings']);

        app(TenantContext::class)->set($tenant);

        $branding = app(BrandingManager::class);

        $this->assertSame('Tenant Without Settings', $branding->name());
        $this->assertSame('without-settings', $branding->slug());
        $this->assertSame('#2563eb', $branding->primaryColor());
        $this->assertSame('#64748b', $branding->secondaryColor());
        $this->assertNull($branding->logoPath());
        $this->assertNull($branding->faviconPath());
        $this->assertSame('system', $branding->theme());
        $this->assertSame('en', $branding->locale());
        $this->assertSame('UTC', $branding->timezone());
        $this->assertSame('USD', $branding->currency());
        $this->assertSame('Y-m-d', $branding->dateFormat());
        $this->assertSame('H:i', $branding->timeFormat());
    }

    public function test_branding_does_not_leak_between_tenants(): void
    {
        $firstTenant = $this->createTenant(['slug' => 'leak-source']);
        $secondTenant = $this->createTenant(['slug' => 'leak-target']);
        TenantSettings::query()->create([
            'tenant_id' => $firstTenant->getKey(),
            'primary_color' => '#dc2626',
        ]);

        $context = app(TenantContext::class);
        $branding = app(BrandingManager::class);

        $context->set($firstTenant);
        $this->assertSame('#dc2626', $branding->primaryColor());

        $context->clear();
        $branding->clear();
        $context->set($secondTenant);

        $this->assertSame('#2563eb', $branding->primaryColor());
    }
}
