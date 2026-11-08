<?php

namespace App\Core\Capabilities;

use App\Core\Billing\Models\PlanCapability;
use App\Core\Billing\Models\Subscription;
use App\Core\Capabilities\Contracts\CapabilityRegistryContract;
use App\Core\Capabilities\Models\TenantCapability;
use App\Core\Tenancy\Contracts\TenantContract;
use App\Core\Tenancy\Services\TenantContext;

class TenantCapabilityResolver
{
    public function __construct(
        private readonly TenantContext $context,
        private readonly CapabilityRegistryContract $registry,
    ) {}

    public function enabled(string $capability, ?TenantContract $tenant = null): bool
    {
        $tenant ??= $this->context->get();

        if ($tenant === null) {
            return $this->registry->enabled($capability);
        }

        $previous = $this->context->get();
        $this->context->set($tenant);

        try {
            $override = TenantCapability::query()->where('capability', $capability)->first();

            if ($override !== null) {
                return $override->enabled;
            }

            $subscription = Subscription::query()
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if ($subscription !== null) {
                $planCapability = PlanCapability::query()
                    ->where('plan_id', $subscription->plan_id)
                    ->where('capability', $capability)
                    ->first();

                if ($planCapability !== null) {
                    return $planCapability->enabled;
                }
            }

            return $this->registry->enabled($capability);
        } finally {
            if ($previous === null) {
                $this->context->clear();
            } else {
                $this->context->set($previous);
            }
        }
    }
}
