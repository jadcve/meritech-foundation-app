<?php

namespace App\Core\Tenancy\Models;

use App\Core\Tenancy\Concerns\BelongsToTenant;
use App\Core\Tenancy\Scoping\TenantScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenantMembership extends Pivot
{
    use BelongsToTenant;

    protected $table = 'tenant_memberships';

    public $incrementing = true;

    protected $primaryKey = 'id';

    protected $fillable = ['tenant_id', 'user_id', 'is_active', 'is_default'];

    protected static function booted(): void
    {
        static::saving(function (TenantMembership $membership): void {
            if ($membership->is_default !== true) {
                return;
            }

            static::withoutGlobalScope(TenantScope::class)
                ->where('user_id', $membership->user_id)
                ->when($membership->exists, fn ($query) => $query->where($membership->getKeyName(), '!=', $membership->getKey()))
                ->update(['is_default' => false]);
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
