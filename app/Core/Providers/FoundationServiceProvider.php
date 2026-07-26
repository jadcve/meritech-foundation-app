<?php

namespace App\Core\Providers;

use App\Core\Branding\BrandingManager;
use App\Core\Capabilities\Contracts\CapabilityRegistryContract;
use App\Core\Capabilities\Registry\CapabilityRegistry;
use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Services\TenantContext;
use App\Core\Tenancy\Services\TenantResolver;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/foundation.php', 'foundation');
        $this->mergeConfigFrom(__DIR__.'/../../../config/capabilities.php', 'capabilities');
        $this->app->singleton(CapabilityRegistryContract::class, fn () => new CapabilityRegistry(config('capabilities', [])));
        $this->app->scoped(BrandingManager::class);
        $this->app->scoped(TenantContext::class);
        $this->app->bind(TenantResolverContract::class, TenantResolver::class);
    }
}
