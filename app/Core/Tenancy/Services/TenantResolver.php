<?php

namespace App\Core\Tenancy\Services;

use App\Core\Tenancy\Contracts\TenantContract;
use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Models\TenantMembership;
use App\Models\User;

class TenantResolver implements TenantResolverContract
{
    public function resolve(User $user): ?TenantContract
    {
        $memberships = TenantMembership::query()
            ->whereBelongsTo($user)
            ->where('is_active', true)
            ->whereHas('tenant', fn ($query) => $query->where('is_active', true))
            ->with('tenant')
            ->get();

        if ($memberships->isEmpty()) {
            return null;
        }

        if ($memberships->count() === 1) {
            return $memberships->first()->tenant;
        }

        $defaultMemberships = $memberships->where('is_default', true);

        if ($defaultMemberships->count() !== 1) {
            return null;
        }

        return $defaultMemberships->first()->tenant;
    }
}
