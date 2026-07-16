<?php

namespace App\Core\Tenancy\Services;

use App\Core\Contracts\TenantContract;

class TenantContext
{
    private ?TenantContract $tenant = null;

    public function set(TenantContract $tenant): void { $this->tenant = $tenant; }
    public function current(): ?TenantContract { return $this->tenant; }
    public function clear(): void { $this->tenant = null; }
    public function id(): int|string|null { return $this->tenant?->getTenantKey(); }
}
