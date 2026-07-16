<?php

namespace App\Core\Providers;

use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Services\TenantContext;
use App\Core\Tenancy\Services\TenantResolver;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/foundation.php', 'foundation');
        $this->app->singleton(TenantContext::class);
        $this->app->bind(TenantResolverContract::class, TenantResolver::class);
    }
}
