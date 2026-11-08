<?php

namespace App\Core\Audit\Models;

use App\Core\Security\Concerns\ProtectsSensitiveAttributes;
use App\Core\Tenancy\Concerns\BelongsToTenant;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use BelongsToTenant, ProtectsSensitiveAttributes;

    public const UPDATED_AT = null;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'summary',
        'properties',
        'ip_address',
        'user_agent',
    ];

    /**
     * @var array<string, string>
     */
    protected array $sensitive = [
        'properties' => 'encrypted:array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
