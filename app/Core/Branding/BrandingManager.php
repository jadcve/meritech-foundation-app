<?php

namespace App\Core\Branding;

use App\Core\Tenancy\Models\TenantSettings;
use App\Core\Tenancy\Services\TenantContext;

class BrandingManager
{
    private ?TenantSettings $settings = null;

    private bool $loaded = false;

    public function __construct(private readonly TenantContext $tenantContext) {}

    public function name(): string
    {
        return $this->tenantContext->get()?->getTenantName() ?? config('foundation.name', 'Meritech Foundation');
    }

    public function slug(): ?string
    {
        return $this->tenantContext->get()?->slug;
    }

    public function logoPath(): ?string
    {
        return $this->settings()?->logo_path;
    }

    public function faviconPath(): ?string
    {
        return $this->settings()?->favicon_path;
    }

    public function primaryColor(): string
    {
        return $this->settings()?->primary_color ?? '#2563eb';
    }

    public function secondaryColor(): string
    {
        return $this->settings()?->secondary_color ?? '#64748b';
    }

    public function theme(): string
    {
        return $this->settings()?->theme ?? 'system';
    }

    public function locale(): string
    {
        return $this->settings()?->locale ?? 'en';
    }

    public function timezone(): string
    {
        return $this->settings()?->timezone ?? 'UTC';
    }

    public function currency(): string
    {
        return $this->settings()?->currency ?? 'USD';
    }

    public function dateFormat(): string
    {
        return $this->settings()?->date_format ?? 'Y-m-d';
    }

    public function timeFormat(): string
    {
        return $this->settings()?->time_format ?? 'H:i';
    }

    public function clear(): void
    {
        $this->settings = null;
        $this->loaded = false;
    }

    private function settings(): ?TenantSettings
    {
        if ($this->loaded) {
            return $this->settings;
        }

        $tenant = $this->tenantContext->get();

        $this->settings = $tenant?->settings()->first();
        $this->loaded = true;

        return $this->settings;
    }
}
