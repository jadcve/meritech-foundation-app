<?php

namespace App\Core\Tenancy\Models;

use App\Core\Contracts\TenantContract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model implements TenantContract
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_memberships')->withPivot(['role', 'is_active'])->withTimestamps();
    }

    public function getTenantKey(): int|string
    {
        return $this->getKey();
    }

    public function getTenantName(): string
    {
        return $this->name;
    }

    public function isTenantActive(): bool
    {
        return $this->is_active;
    }
}
