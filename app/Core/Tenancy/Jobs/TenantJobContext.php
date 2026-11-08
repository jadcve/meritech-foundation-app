<?php

namespace App\Core\Tenancy\Jobs;

use App\Core\Tenancy\Contracts\TenantContract;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Services\TenantContext;

class TenantJobContext
{
    /**
     * @var array<int, TenantContract|null>
     */
    private array $stack = [];

    public function __construct(private readonly TenantContext $context) {}

    public function enter(mixed $tenantId): void
    {
        $this->stack[] = $this->context->get();

        if ($tenantId === null || $tenantId === '') {
            return;
        }

        $tenant = Tenant::query()->find($tenantId);

        if ($tenant !== null) {
            $this->context->set($tenant);
        }
    }

    public function leave(): void
    {
        if ($this->stack === []) {
            $this->context->clear();

            return;
        }

        $previous = array_pop($this->stack);

        if ($previous instanceof TenantContract) {
            $this->context->set($previous);

            return;
        }

        $this->context->clear();
    }
}
