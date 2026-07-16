<?php

namespace App\Core\Contracts;

interface TenantContract
{
    public function getTenantKey(): int|string;
    public function getTenantName(): string;
    public function isTenantActive(): bool;
}
