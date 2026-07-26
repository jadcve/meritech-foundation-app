<?php

namespace App\Core\Capabilities\Contracts;

interface CapabilityProviderContract
{
    public function registerCapability(CapabilityRegistryContract $registry): void;
}
