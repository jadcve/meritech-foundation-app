<?php

namespace App\Core\Tenancy\Services;

use App\Core\Contracts\TenantContract;
use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class TenantResolver implements TenantResolverContract
{
    public function resolve(User $user): ?TenantContract
    {
        return Tenant::query()->whereHas('users', fn ($query) => $query->whereKey($user->getKey())->where('tenant_memberships.is_active', true))->where('is_active', true)->first();
    }
}
