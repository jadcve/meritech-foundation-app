<?php

namespace App\Core\Authorization;

use App\Core\Authorization\Exceptions\TenantAuthorizationContextMissingException;
use App\Core\Tenancy\Contracts\TenantContract;
use App\Core\Tenancy\Services\TenantContext;
use Spatie\Permission\PermissionRegistrar;

final class TenantAuthorizationContext
{
    public function __construct(
        private readonly TenantContext $tenantContext,
        private readonly PermissionRegistrar $permissionRegistrar,
    ) {}

    public function activate(TenantContract $tenant): void
    {
        $this->permissionRegistrar->setPermissionsTeamId($tenant->getTenantKey());
    }

    public function activateCurrentTenant(): void
    {
        $tenant = $this->tenantContext->get();

        if ($tenant === null) {
            $this->clear();

            throw new TenantAuthorizationContextMissingException;
        }

        $this->activate($tenant);
    }

    public function clear(): void
    {
        $this->permissionRegistrar->setPermissionsTeamId(null);
    }

    public function id(): int|string|null
    {
        return $this->permissionRegistrar->getPermissionsTeamId();
    }

    public function hasTenant(): bool
    {
        return $this->id() !== null;
    }
}
