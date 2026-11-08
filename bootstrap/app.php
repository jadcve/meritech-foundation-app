<?php

use App\Core\Authorization\Exceptions\TenantAuthorizationContextMissingException;
use App\Core\Authorization\Middleware\ActivateTenantAuthorization;
use App\Core\Capabilities\Middleware\EnsureModuleEnabled;
use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Exceptions\TenantIsolationException;
use App\Core\Tenancy\Exceptions\TenantNotResolvedException;
use App\Core\Tenancy\Middleware\ResolveTenant;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'tenant.authorization' => ActivateTenantAuthorization::class,
            'tenant.resolve' => ResolveTenant::class,
            'module' => EnsureModuleEnabled::class,
        ]);

        $middleware->appendToPriorityList(
            AuthenticatesRequests::class,
            ResolveTenant::class,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(fn (TenantAuthorizationContextMissingException $exception, Request $request) => response()->json([
            'message' => $exception->getMessage(),
        ], 403));

        $exceptions->render(fn (TenantNotResolvedException $exception, Request $request) => response()->json([
            'message' => $exception->getMessage(),
        ], 403));

        $exceptions->render(fn (MissingTenantContextException $exception, Request $request) => response()->json([
            'message' => $exception->getMessage(),
        ], 403));

        $exceptions->render(fn (TenantIsolationException $exception, Request $request) => response()->json([
            'message' => $exception->getMessage(),
        ], 403));

        $exceptions->shouldRenderJsonWhen(fn (Request $request) => $request->is('api/*'));
    })
    ->create();
