<?php

namespace Tests\Feature\Capabilities;

use App\Core\Billing\Contracts\SubscriptionProvider;
use App\Core\Billing\Models\Plan;
use App\Core\Billing\Models\PlanCapability;
use App\Core\Capabilities\Models\TenantCapability;
use App\Core\Capabilities\TenantCapabilityResolver;
use App\Core\Tenancy\Services\TenantContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\Support\CreatesTenantMemberships;
use Tests\TestCase;

class TenantModuleTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_plan_defaults_and_tenant_overrides_control_modules(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tenantA = $this->attachTenant($userA);
        $tenantB = $this->attachTenant($userB);
        $plan = Plan::query()->create(['code' => 'salud-base', 'name' => 'Salud base']);
        PlanCapability::query()->create(['plan_id' => $plan->getKey(), 'capability' => 'salud', 'enabled' => true]);
        PlanCapability::query()->create(['plan_id' => $plan->getKey(), 'capability' => 'taller', 'enabled' => false]);

        $provider = app(SubscriptionProvider::class);
        $provider->subscribe($tenantA, $plan);

        $resolver = app(TenantCapabilityResolver::class);
        $this->assertTrue($resolver->enabled('salud', $tenantA));
        $this->assertFalse($resolver->enabled('taller', $tenantA));
        $this->assertFalse($resolver->enabled('salud', $tenantB));

        app(TenantContext::class)->set($tenantA);
        TenantCapability::query()->create(['capability' => 'salud', 'enabled' => false]);
        $this->assertFalse($resolver->enabled('salud', $tenantA));

        $this->actingAs($userA)->get('/tenant/modules/salud')->assertForbidden();
        $this->actingAs($userB)->get('/tenant/modules/salud')->assertForbidden();

        app(TenantContext::class)->set($tenantA);
        TenantCapability::query()->where('capability', 'salud')->update(['enabled' => true]);
        $this->actingAs($userA)->get('/tenant/modules/salud')->assertOk()->assertSee('salud');

        app(TenantContext::class)->set($tenantA);
        $this->assertTrue(Gate::forUser($userA)->allows('module', 'salud'));
    }

    public function test_local_provider_cancels_the_previous_subscription(): void
    {
        $tenant = $this->createTenant(['slug' => 'plan-switch']);
        $first = Plan::query()->create(['code' => 'free', 'name' => 'Libre']);
        $second = Plan::query()->create(['code' => 'plus', 'name' => 'Plus']);
        $provider = app(SubscriptionProvider::class);

        $original = $provider->subscribe($tenant, $first);
        $replacement = $provider->subscribe($tenant, $second);

        app(TenantContext::class)->set($tenant);
        $this->assertSame('canceled', $original->fresh()->status);
        $this->assertSame('active', $replacement->fresh()->status);
        $this->assertTrue($provider->current($tenant)->is($replacement));
        $this->assertSame('local', $provider->name());
    }
}
