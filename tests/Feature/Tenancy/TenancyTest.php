<?php

namespace Tests\Feature\Tenancy;

use App\Core\Tenancy\Services\TenantBypassPolicy;
use App\Core\Tenancy\Services\TenantContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\Support\CreatesTenantMemberships;
use Tests\TestCase;

class TenancyTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_user_with_one_active_tenant_resolves_automatically(): void
    {
        $user = User::factory()->create();
        $tenant = $this->attachTenant($user);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertOk()
            ->assertJson(['tenant_id' => $tenant->getKey()]);
    }

    public function test_user_with_default_tenant_resolves_default_membership(): void
    {
        $user = User::factory()->create();
        $tenant = $this->attachTenant($user, attributes: ['is_default' => true]);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertOk()
            ->assertJson(['tenant_id' => $tenant->getKey()]);
    }

    public function test_user_with_multiple_tenants_and_one_default_resolves_default(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user, $this->createTenant(['slug' => 'non-default-tenant']));
        $defaultTenant = $this->attachTenant($user, $this->createTenant(['slug' => 'default-tenant']), ['is_default' => true]);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertOk()
            ->assertJson(['tenant_id' => $defaultTenant->getKey()]);
    }

    public function test_user_can_have_only_one_default_membership(): void
    {
        $user = User::factory()->create();
        $firstTenant = $this->attachTenant($user, $this->createTenant(['slug' => 'first-default-tenant']), ['is_default' => true]);
        $secondTenant = $this->attachTenant($user, $this->createTenant(['slug' => 'second-default-tenant']), ['is_default' => true]);

        $this->assertFalse($user->tenantMemberships()->whereBelongsTo($firstTenant)->first()->is_default);
        $this->assertTrue($user->tenantMemberships()->whereBelongsTo($secondTenant)->first()->is_default);
        $this->assertSame(1, $user->tenantMemberships()->where('is_default', true)->count());
    }

    public function test_user_with_multiple_tenants_and_no_default_fails_closed(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user, $this->createTenant(['slug' => 'first-non-default-tenant']));
        $this->attachTenant($user, $this->createTenant(['slug' => 'second-non-default-tenant']));

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertForbidden()
            ->assertJsonPath('message', 'A tenant is required for this route, but no active tenant membership could be resolved.');
    }

    public function test_user_without_memberships_fails_closed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertForbidden();
    }

    public function test_inactive_membership_is_not_resolved(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user, attributes: ['is_active' => false, 'is_default' => true]);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertForbidden();
    }

    public function test_inactive_tenant_is_not_resolved(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user, $this->createTenant(['is_active' => false]), ['is_default' => true]);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertForbidden();
    }

    public function test_tenant_context_set_get_id_has_tenant_and_clear(): void
    {
        $context = app(TenantContext::class);
        $tenant = $this->createTenant();

        $this->assertFalse($context->hasTenant());
        $this->assertNull($context->get());
        $this->assertNull($context->id());

        $context->set($tenant);

        $this->assertTrue($context->hasTenant());
        $this->assertTrue($context->get()->is($tenant));
        $this->assertSame($tenant->getKey(), $context->id());

        $context->clear();

        $this->assertFalse($context->hasTenant());
        $this->assertNull($context->get());
    }

    public function test_middleware_resolves_tenant(): void
    {
        $user = User::factory()->create();
        $tenant = $this->attachTenant($user);

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertOk()
            ->assertJson(['tenant_id' => $tenant->getKey()]);
    }

    public function test_auth_and_global_profile_routes_do_not_require_tenant(): void
    {
        $user = User::factory()->create();

        $this->get('/login')->assertOk();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();
    }

    public function test_tenant_dashboard_requires_tenant(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/tenant/dashboard')
            ->assertForbidden();
    }

    public function test_tenant_dashboard_resolves_tenant_when_membership_exists(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user);

        $this->actingAs($user)
            ->get('/tenant/dashboard')
            ->assertOk();
    }

    public function test_first_tenant_is_not_selected_arbitrarily(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user, $this->createTenant(['slug' => 'first-arbitrary-tenant']));
        $this->attachTenant($user, $this->createTenant(['slug' => 'second-arbitrary-tenant']));

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertForbidden();
    }

    public function test_context_is_isolated_between_requests(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $firstTenant = $this->attachTenant($firstUser, $this->createTenant(['slug' => 'first-isolated-tenant']));
        $secondTenant = $this->attachTenant($secondUser, $this->createTenant(['slug' => 'second-isolated-tenant']));
        $uri = $this->tenantProbeUri(__FUNCTION__);

        $this->actingAs($firstUser)
            ->getJson($uri)
            ->assertOk()
            ->assertJson(['tenant_id' => $firstTenant->getKey()]);

        $this->actingAs($secondUser)
            ->getJson($uri)
            ->assertOk()
            ->assertJson(['tenant_id' => $secondTenant->getKey()]);
    }

    public function test_superadmin_bypass_can_operate_without_tenant_when_policy_allows_it(): void
    {
        config(['foundation.tenancy.bypass.enabled' => true]);

        $this->app->instance(TenantBypassPolicy::class, new class extends TenantBypassPolicy
        {
            public function allows(User $user): bool
            {
                return true;
            }
        });

        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson($this->tenantProbeUri(__FUNCTION__))
            ->assertOk()
            ->assertJson(['tenant_id' => null]);
    }

    private function tenantProbeUri(string $name): string
    {
        $uri = '/__tenancy_probe/'.Str::slug($name, '_');

        Route::get($uri, fn () => response()->json([
            'tenant_id' => app(TenantContext::class)->id(),
        ]))->middleware(['web', 'auth', 'tenant.resolve']);

        return $uri;
    }
}
