<?php

namespace App\Core\Capabilities\Models;

use App\Core\Tenancy\Concerns\BelongsToTenant;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantCapability extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'capability', 'enabled'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean'];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
