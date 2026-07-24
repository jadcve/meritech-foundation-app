<?php

namespace App\Core\Authorization\Middleware;

use App\Core\Authorization\TenantAuthorizationContext;
use Closure;
use Illuminate\Http\Request;

class ActivateTenantAuthorization
{
    public function __construct(private readonly TenantAuthorizationContext $authorizationContext) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $this->authorizationContext->activateCurrentTenant();

        try {
            return $next($request);
        } finally {
            $this->authorizationContext->clear();
        }
    }
}
