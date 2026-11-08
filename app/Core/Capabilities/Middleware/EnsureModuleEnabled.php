<?php

namespace App\Core\Capabilities\Middleware;

use App\Core\Capabilities\TenantCapabilityResolver;
use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function __construct(private readonly TenantCapabilityResolver $resolver) {}

    public function handle(Request $request, Closure $next, ?string $module = null): mixed
    {
        $module ??= $request->route('module');

        if (! is_string($module) || $module === '' || ! $this->resolver->enabled($module)) {
            abort(403, 'The module is not enabled for the active tenant.');
        }

        return $next($request);
    }
}
