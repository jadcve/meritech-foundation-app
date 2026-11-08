<?php

namespace App\Core\Tenancy\Jobs\Concerns;

use App\Core\Tenancy\Jobs\Middleware\RequireTenantContext;

trait RequiresTenantContext
{
    /**
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new RequireTenantContext];
    }
}
