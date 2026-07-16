<?php

use App\Core\Tenancy\Middleware\ResolveTenant;
use App\Core\Tenancy\Exceptions\TenantNotResolvedException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['tenant.resolve' => ResolveTenant::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(fn (TenantNotResolvedException $exception, Request $request) => response()->json([
            'message' => $exception->getMessage(),
        ], 403));

        $exceptions->shouldRenderJsonWhen(fn (Request $request) => $request->is('api/*'));
    })
    ->create();
