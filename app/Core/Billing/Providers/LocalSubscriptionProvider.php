<?php

namespace App\Core\Billing\Providers;

use App\Core\Billing\Contracts\SubscriptionProvider;
use App\Core\Billing\Models\Plan;
use App\Core\Billing\Models\Subscription;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Services\TenantContext;

class LocalSubscriptionProvider implements SubscriptionProvider
{
    public function __construct(private readonly TenantContext $context) {}

    public function name(): string
    {
        return 'local';
    }

    public function subscribe(Tenant $tenant, Plan $plan): Subscription
    {
        $previous = $this->context->get();
        $this->context->set($tenant);

        try {
            Subscription::query()
                ->where('status', 'active')
                ->update(['status' => 'canceled', 'ends_at' => now()]);

            return Subscription::query()->create([
                'tenant_id' => $tenant->getKey(),
                'plan_id' => $plan->getKey(),
                'status' => 'active',
                'provider' => $this->name(),
                'starts_at' => now(),
            ]);
        } finally {
            $this->restore($previous);
        }
    }

    public function cancel(Subscription $subscription): Subscription
    {
        $subscription->forceFill([
            'status' => 'canceled',
            'ends_at' => now(),
        ])->save();

        return $subscription;
    }

    public function current(Tenant $tenant): ?Subscription
    {
        $previous = $this->context->get();
        $this->context->set($tenant);

        try {
            return Subscription::query()
                ->where('status', 'active')
                ->latest('id')
                ->first();
        } finally {
            $this->restore($previous);
        }
    }

    private function restore(mixed $previous): void
    {
        if ($previous === null) {
            $this->context->clear();

            return;
        }

        $this->context->set($previous);
    }
}
