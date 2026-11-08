<?php

namespace App\Core\Audit;

use App\Core\Audit\Models\AuditLog;
use App\Core\Capabilities\TenantCapabilityResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public function __construct(private readonly TenantCapabilityResolver $capabilities) {}

    /**
     * @param  array<string, mixed>  $properties
     */
    public function record(string $event, ?Model $subject = null, array $properties = [], ?string $summary = null): ?AuditLog
    {
        if (! $this->capabilities->enabled('audit')) {
            return null;
        }

        return AuditLog::query()->create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $subject?->getMorphClass(),
            'auditable_id' => $subject?->getKey(),
            'summary' => $summary,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function viewed(Model $subject, array $properties = [], ?string $summary = null): ?AuditLog
    {
        return $this->record('viewed', $subject, $properties, $summary);
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    public function changed(Model $subject, array $properties = [], ?string $summary = null): ?AuditLog
    {
        return $this->record('updated', $subject, $properties, $summary);
    }
}
