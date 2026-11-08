<?php

namespace App\Core\Tenancy\Concerns;

use App\Core\Tenancy\Scoping\TenantScope;
use App\Core\Tenancy\Services\TenantGuard;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (Model $model): void {
            app(TenantGuard::class)->prepareForWrite($model);
        });

        static::updating(function (Model $model): void {
            app(TenantGuard::class)->prepareForWrite($model);
        });
    }
}
