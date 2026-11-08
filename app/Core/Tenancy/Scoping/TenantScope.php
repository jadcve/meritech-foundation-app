<?php

namespace App\Core\Tenancy\Scoping;

use App\Core\Tenancy\Services\TenantContext;
use App\Core\Tenancy\Services\TenantIsolation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app(TenantIsolation::class)->bypassed()) {
            return;
        }

        $tenantId = app(TenantContext::class)->id();

        if ($tenantId === null) {
            $builder->whereRaw('0 = 1');

            return;
        }

        $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
    }
}
