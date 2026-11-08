<?php

namespace App\Core\Tenancy\Jobs\Middleware;

use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Services\TenantContext;
use Closure;

class RequireTenantContext
{
    public function handle(object $job, Closure $next): mixed
    {
        if (app(TenantContext::class)->id() === null) {
            throw new MissingTenantContextException;
        }

        return $next($job);
    }
}
