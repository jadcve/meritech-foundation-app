<?php

namespace App\Core\Tenancy\Middleware;

use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Exceptions\TenantNotResolvedException;
use App\Core\Tenancy\Services\TenantBypassPolicy;
use App\Core\Tenancy\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (($user = $request->user()) !== null) {
            if (app(TenantBypassPolicy::class)->allows($user)) {
                return $next($request);
            }

            $tenant = app(TenantResolverContract::class)->resolve($user);

            if ($tenant !== null) {
                app(TenantContext::class)->set($tenant);
            } elseif (config('foundation.tenancy.fail_closed', true)) {
                throw new TenantNotResolvedException;
            }
        }

        try {
            return $next($request);
        } finally {
            app(TenantContext::class)->clear();
        }
    }
}
