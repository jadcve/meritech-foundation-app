<?php

namespace App\Core\Tenancy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantSettings extends Model
{
    protected $table = 'tenant_settings';

    protected $fillable = [
        'tenant_id',
        'locale',
        'timezone',
        'currency',
        'theme',
        'primary_color',
        'secondary_color',
        'logo_path',
        'favicon_path',
        'date_format',
        'time_format',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
