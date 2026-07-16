<?php

namespace App\Core\Tenancy\Contracts;

use App\Models\User;

interface TenantResolverContract
{
    public function resolve(User $user): ?TenantContract;
}
