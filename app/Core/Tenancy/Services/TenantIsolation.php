<?php

namespace App\Core\Tenancy\Services;

class TenantIsolation
{
    private int $depth = 0;

    public function bypassed(): bool
    {
        return $this->depth > 0;
    }

    public function bypass(callable $callback): mixed
    {
        $this->depth++;

        try {
            return $callback();
        } finally {
            $this->depth--;
        }
    }
}
