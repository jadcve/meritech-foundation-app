<?php

namespace Tests\Support;

use App\Core\Tenancy\Models\TenantMembership;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

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
}
