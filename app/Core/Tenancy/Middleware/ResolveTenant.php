<?php

namespace App\Core\Tenancy\Middleware;

use App\Core\Tenancy\Contracts\TenantResolverContract;
use App\Core\Tenancy\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (($user = $request->user()) !== null && ($tenant = app(TenantResolverContract::class)->resolve($user)) !== null) {
            app(TenantContext::class)->set($tenant);
        }
        try { return $next($request); } finally { app(TenantContext::class)->clear(); }
    }
}
