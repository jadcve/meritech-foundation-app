<?php

namespace App\Providers;

use App\Core\Capabilities\TenantCapabilityResolver;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('module', function (User $user, string $module): bool {
            return app(TenantCapabilityResolver::class)->enabled($module);
        });
    }
}
