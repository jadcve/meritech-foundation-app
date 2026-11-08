<?php

namespace App\Core\Tenancy\Services;

use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Exceptions\TenantIsolationException;
use Illuminate\Database\Eloquent\Model;

class TenantGuard
{
    public function __construct(
        private readonly TenantContext $context,
        private readonly TenantIsolation $isolation,
    ) {}

    public function prepareForWrite(Model $model): void
    {
        if ($this->isolation->bypassed()) {
            return;
        }

        $tenantId = $this->context->id();

        if ($tenantId === null) {
            throw new MissingTenantContextException;
        }

        $assigned = $model->getAttribute('tenant_id');

        if ($assigned === null) {
            $model->setAttribute('tenant_id', $tenantId);

            return;
        }

        if ((string) $assigned !== (string) $tenantId) {
            throw new TenantIsolationException;
        }
    }
}
