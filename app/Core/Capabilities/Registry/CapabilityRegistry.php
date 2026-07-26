<?php

namespace App\Core\Capabilities\Registry;

use App\Core\Capabilities\Contracts\CapabilityRegistryContract;
use App\Core\Capabilities\Support\CapabilityDefinition;

class CapabilityRegistry implements CapabilityRegistryContract
{
    /**
     * @var array<string, CapabilityDefinition>
     */
    private array $capabilities = [];

    public function __construct(array $configuredCapabilities = [])
    {
        foreach ($configuredCapabilities as $name => $enabled) {
            $this->register((string) $name, (bool) $enabled);
        }
    }

    public function register(string $name, bool $enabled = false, array $metadata = []): CapabilityDefinition
    {
        $definition = new CapabilityDefinition($name, $enabled, $metadata);

        $this->capabilities[$name] = $definition;

        return $definition;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->capabilities);
    }

    public function enabled(string $name): bool
    {
        return $this->get($name)?->enabled ?? false;
    }

    public function get(string $name): ?CapabilityDefinition
    {
        return $this->capabilities[$name] ?? null;
    }

    public function all(): array
    {
        return $this->capabilities;
    }

    public function enabledCapabilities(): array
    {
        return array_filter(
            $this->capabilities,
            fn (CapabilityDefinition $capability): bool => $capability->enabled,
        );
    }
}
