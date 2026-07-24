<?php

namespace Tests\Feature\Authorization;

use App\Core\Authorization\FoundationPermissions;
use App\Core\Authorization\TenantAuthorizationContext;
use App\Core\Tenancy\Models\TenantMembership;
use App\Core\Tenancy\Services\TenantBypassPolicy;
use App\Core\Tenancy\Services\TenantContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\Support\CreatesTenantMemberships;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_admin_in_tenant_a_can_access_settings_in_tenant_a(): void
    {
        [$user, $tenant] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        $this->actingAs($user)
            ->patch('/tenant/settings')
            ->assertOk();
    }

    public function test_admin_in_tenant_a_cannot_access_admin_action_in_tenant_b_when_viewer_there(): void
    {
        $user = User::factory()->create();
        $tenantA = $this->attachTenant($user, attributes: ['is_default' => false]);
        $tenantB = $this->attachTenant($user, attributes: ['is_default' => true]);
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenantA, FoundationPermissions::ADMIN);
        $this->assignTenantRole($user, $tenantB, FoundationPermissions::VIEWER);

        $this->actingAs($user)
            ->patch('/tenant/settings')
            ->assertForbidden();
    }

    public function test_role_assigned_in_one_tenant_is_not_visible_in_another(): void
    {
        $user = User::factory()->create();
        $tenantA = $this->attachTenant($user);
        $tenantB = $this->createTenant();
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenantA, FoundationPermissions::ADMIN);

        $this->activatePermissionTeam($tenantA);
        $this->assertTrue($user->fresh()->hasRole(FoundationPermissions::ADMIN));

        $this->activatePermissionTeam($tenantB);
        $this->assertFalse($user->fresh()->hasRole(FoundationPermissions::ADMIN));

        $this->clearPermissionTeam();
    }

    public function test_permission_assigned_in_one_tenant_is_not_visible_in_another(): void
    {
        $user = User::factory()->create();
        $tenantA = $this->attachTenant($user);
        $tenantB = $this->createTenant();
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenantA, FoundationPermissions::ADMIN);

        $this->activatePermissionTeam($tenantA);
        $this->assertTrue($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));

        $this->activatePermissionTeam($tenantB);
        $this->assertFalse($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));

        $this->clearPermissionTeam();
    }

    public function test_user_with_memberships_in_multiple_tenants_keeps_different_roles_per_tenant(): void
    {
        $user = User::factory()->create();
        $tenantA = $this->attachTenant($user);
        $tenantB = $this->attachTenant($user, attributes: ['is_default' => true]);
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenantA, FoundationPermissions::ADMIN);
        $this->assignTenantRole($user, $tenantB, FoundationPermissions::VIEWER);

        $this->activatePermissionTeam($tenantA);
        $this->assertTrue($user->fresh()->hasRole(FoundationPermissions::ADMIN));

        $this->activatePermissionTeam($tenantB);
        $this->assertTrue($user->fresh()->hasRole(FoundationPermissions::VIEWER));
        $this->assertFalse($user->fresh()->hasRole(FoundationPermissions::ADMIN));

        $this->clearPermissionTeam();
    }

    public function test_user_without_membership_in_resolved_tenant_is_denied(): void
    {
        $user = User::factory()->create();
        $tenant = $this->createTenant();
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenant, FoundationPermissions::ADMIN);

        $this->actingAs($user)
            ->get('/tenant/settings')
            ->assertForbidden();
    }

    public function test_removing_membership_removes_effective_access(): void
    {
        [$user] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        TenantMembership::query()->whereBelongsTo($user)->delete();

        $this->actingAs($user)
            ->get('/tenant/settings')
            ->assertForbidden();
    }

    public function test_membership_without_role_grants_no_elevated_permission(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user);
        $this->seedFoundationAuthorization();

        $this->actingAs($user)
            ->get('/tenant/settings')
            ->assertForbidden();
    }

    public function test_tenant_memberships_contains_no_role_related_field(): void
    {
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'role'));
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'role_id'));
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'permission'));
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'permissions'));
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'is_admin'));
        $this->assertFalse(Schema::hasColumn('tenant_memberships', 'is_owner'));
    }

    public function test_tenant_scoped_permission_check_without_tenant_context_fails_closed(): void
    {
        [$user] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        $this->assertFalse(app(TenantAuthorizationContext::class)->hasTenant());
        $this->assertFalse($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));
    }

    public function test_permission_middleware_without_tenant_resolve_is_denied_safely(): void
    {
        [$user] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        Route::get('/__authorization_without_tenant', fn () => response('ok'))
            ->middleware(['web', 'auth', 'tenant.authorization', 'permission:settings.view']);

        $this->actingAs($user)
            ->getJson('/__authorization_without_tenant')
            ->assertForbidden()
            ->assertJsonPath('message', 'Tenant authorization requires an active tenant context.');
    }

    public function test_authorization_context_is_cleared_between_requests(): void
    {
        [$user] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        $this->actingAs($user)->get('/tenant/settings')->assertOk();

        $this->assertFalse(app(TenantAuthorizationContext::class)->hasTenant());
    }

    public function test_owner_receives_all_foundation_tenant_permissions(): void
    {
        [$user, $tenant] = $this->userWithTenantRole(FoundationPermissions::OWNER);

        $this->activatePermissionTeam($tenant);

        foreach (FoundationPermissions::permissions() as $permission) {
            $this->assertTrue($user->fresh()->can($permission), $permission);
        }

        $this->clearPermissionTeam();
    }

    public function test_admin_is_not_equivalent_to_owner(): void
    {
        [$user, $tenant] = $this->userWithTenantRole(FoundationPermissions::ADMIN);

        $this->activatePermissionTeam($tenant);

        $this->assertTrue($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));
        $this->assertFalse($user->fresh()->can(FoundationPermissions::ROLES_MANAGE));

        $this->clearPermissionTeam();
    }

    public function test_member_cannot_manage_roles(): void
    {
        [$user, $tenant] = $this->userWithTenantRole(FoundationPermissions::MEMBER);

        $this->activatePermissionTeam($tenant);

        $this->assertFalse($user->fresh()->can(FoundationPermissions::ROLES_MANAGE));

        $this->clearPermissionTeam();
    }

    public function test_viewer_cannot_update_tenant_settings(): void
    {
        [$user, $tenant] = $this->userWithTenantRole(FoundationPermissions::VIEWER);

        $this->activatePermissionTeam($tenant);

        $this->assertFalse($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));

        $this->clearPermissionTeam();
    }

    public function test_direct_permission_assignment_remains_tenant_scoped(): void
    {
        $user = User::factory()->create();
        $tenantA = $this->attachTenant($user);
        $tenantB = $this->createTenant();
        $this->seedFoundationAuthorization();

        $this->activatePermissionTeam($tenantA);
        $user->givePermissionTo(FoundationPermissions::SETTINGS_UPDATE);

        $this->assertTrue($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));

        $this->activatePermissionTeam($tenantB);
        $this->assertFalse($user->fresh()->can(FoundationPermissions::SETTINGS_UPDATE));

        $this->clearPermissionTeam();
    }

    public function test_tenant_bypass_does_not_grant_tenant_permissions(): void
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
            ->get('/tenant/settings')
            ->assertForbidden();
    }

    public function test_global_platform_role_concept_does_not_grant_tenant_permission(): void
    {
        $user = User::factory()->create();
        $this->attachTenant($user);
        $this->seedFoundationAuthorization();

        $this->clearPermissionTeam();
        Role::findOrCreate('platform.support', 'web');

        $this->actingAs($user)
            ->patch('/tenant/settings')
            ->assertForbidden();
    }

    public function test_auth_runs_before_tenant_authorization(): void
    {
        $this->get('/tenant/settings')
            ->assertRedirect('/login');
    }

    public function test_protected_route_denies_access_when_tenant_resolution_fails(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/tenant/settings')
            ->assertForbidden();
    }

    private function userWithTenantRole(string $role): array
    {
        $user = User::factory()->create();
        $tenant = $this->attachTenant($user);
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($user, $tenant, $role);

        return [$user, $tenant];
    }
}
