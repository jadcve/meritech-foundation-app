<?php

namespace App\Core\Providers;

use App\Core\Billing\Contracts\SubscriptionProvider;
use App\Core\Billing\Providers\LocalSubscriptionProvider;
use App\Core\Branding\BrandingManager;
use App\Core\Capabilities\Contracts\CapabilityRegistryContract;
use App\Core\Capabilities\Registry\CapabilityRegistry;
use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Jobs\TenantJobContext;
use App\Core\Tenancy\Services\TenantContext;
use App\Core\Tenancy\Services\TenantIsolation;
use App\Core\Tenancy\Services\TenantResolver;
use Illuminate\Queue\Events\JobAttempted;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
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
        $this->app->singleton(TenantIsolation::class);
        $this->app->singleton(TenantJobContext::class);
        $this->app->bind(TenantResolverContract::class, TenantResolver::class);
        $this->app->bind(SubscriptionProvider::class, LocalSubscriptionProvider::class);
    }

    public function boot(): void
    {
        Queue::createPayloadUsing(function (): array {
            return ['foundation_tenant_id' => app(TenantContext::class)->id()];
        });

        Event::listen(JobProcessing::class, function (JobProcessing $event): void {
            app(TenantJobContext::class)->enter($event->job->payload()['foundation_tenant_id'] ?? null);
        });

        Event::listen(JobAttempted::class, function (): void {
            app(TenantJobContext::class)->leave();
        });
    }
}
