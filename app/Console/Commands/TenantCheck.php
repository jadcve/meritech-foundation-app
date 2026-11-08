<?php

namespace App\Console\Commands;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Models\TenantSettings;
use App\Core\Tenancy\Services\TenantContext;
use Illuminate\Console\Command;

class TenantCheck extends Command
{
    protected $signature = 'foundation:tenant-check {tenant : Tenant id or slug}';

    protected $description = 'Bind a tenant and print the settings visible inside that context.';

    public function handle(TenantContext $context): int
    {
        $key = (string) $this->argument('tenant');
        $tenant = Tenant::query()->whereKey($key)->orWhere('slug', $key)->first();

        if ($tenant === null) {
            $this->error('Tenant not found.');

            return self::FAILURE;
        }

        $context->set($tenant);
        $colors = TenantSettings::query()->orderBy('primary_color')->pluck('primary_color')->implode(',');
        $this->line($colors);

        return self::SUCCESS;
    }
}
