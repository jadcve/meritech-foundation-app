<?php

namespace App\Core\Tenancy\Services;

use App\Core\Tenancy\Contracts\TenantContract;

class TenantContext
{
    private ?TenantContract $tenant = null;

    public function set(TenantContract $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?TenantContract
    {
        return $this->tenant;
    }

    public function current(): ?TenantContract
    {
        return $this->get();
    }

    public function clear(): void
    {
        $this->tenant = null;
    }

    public function id(): int|string|null
    {
        return $this->tenant?->getTenantKey();
    }

    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }
}
