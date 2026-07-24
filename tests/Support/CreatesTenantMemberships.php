<?php

namespace Tests\Support;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Models\TenantMembership;
use App\Models\User;
use Database\Seeders\FoundationPermissionSeeder;
use Database\Seeders\FoundationRoleSeeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait CreatesTenantMemberships
{
    protected function createTenant(array $attributes = []): Tenant
    {
        return Tenant::query()->create(array_merge([
            'name' => 'Foundation Tenant',
            'slug' => 'foundation-tenant-'.Str::uuid(),
            'is_active' => true,
        ], $attributes));
    }

    protected function attachTenant(User $user, ?Tenant $tenant = null, array $attributes = []): Tenant
    {
        $tenant ??= $this->createTenant();

        TenantMembership::query()->create(array_merge([
            'tenant_id' => $tenant->getKey(),
            'user_id' => $user->getKey(),
            'is_active' => true,
            'is_default' => false,
        ], $attributes));

        return $tenant;
    }

    protected function assignTenantRole(User $user, Tenant $tenant, string $role): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->getKey());

        $user->assignRole(Role::findOrCreate($role, 'web'));

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        $user->unsetRelation('roles');
    }

    protected function seedFoundationAuthorization(): void
    {
        $this->seed(FoundationPermissionSeeder::class);
        $this->seed(FoundationRoleSeeder::class);
    }

    protected function activatePermissionTeam(Tenant $tenant): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->getKey());
    }

    protected function clearPermissionTeam(): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
    }
}
