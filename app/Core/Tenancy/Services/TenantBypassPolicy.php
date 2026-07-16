<?php

namespace App\Core\Tenancy\Services;

use App\Models\User;

class TenantBypassPolicy
{
    public function allows(User $user): bool
    {
        if (config('foundation.tenancy.bypass.enabled') !== true) {
            return false;
        }

        if (method_exists($user, 'canBypassTenantResolution')) {
            return $user->canBypassTenantResolution() === true;
        }

        return false;
    }
}
