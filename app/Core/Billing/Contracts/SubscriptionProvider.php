<?php

namespace App\Core\Billing\Contracts;

use App\Core\Billing\Models\Plan;
use App\Core\Billing\Models\Subscription;
use App\Core\Tenancy\Models\Tenant;

interface SubscriptionProvider
{
    public function name(): string;

    public function subscribe(Tenant $tenant, Plan $plan): Subscription;

    public function cancel(Subscription $subscription): Subscription;

    public function current(Tenant $tenant): ?Subscription;
}
