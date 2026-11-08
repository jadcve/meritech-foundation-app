<?php

namespace Tests\Support\Jobs;

use App\Core\Tenancy\Jobs\Concerns\RequiresTenantContext;
use App\Core\Tenancy\Models\TenantSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TouchTenantSettings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, RequiresTenantContext, SerializesModels;

    /**
     * @var array<int, string>
     */
    public static array $colors = [];

    public function handle(): void
    {
        self::$colors = TenantSettings::query()->orderBy('primary_color')->pluck('primary_color')->all();
    }
}
