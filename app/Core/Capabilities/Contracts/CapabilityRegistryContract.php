<?php

namespace App\Core\Capabilities\Contracts;

use App\Core\Capabilities\Support\CapabilityDefinition;

interface CapabilityRegistryContract
{
    public function register(string $name, bool $enabled = false, array $metadata = []): CapabilityDefinition;

    public function has(string $name): bool;

    public function enabled(string $name): bool;

    public function get(string $name): ?CapabilityDefinition;

    /**
     * @return array<string, CapabilityDefinition>
     */
    public function all(): array;

    /**
     * @return array<string, CapabilityDefinition>
     */
    public function enabledCapabilities(): array;
}
