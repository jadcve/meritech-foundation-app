<?php

namespace App\Core\Capabilities\Support;

use App\Core\Capabilities\Contracts\CapabilityRegistryContract;

final class Capability
{
    public static function register(string $name, bool $enabled = false, array $metadata = []): CapabilityDefinition
    {
        return self::registry()->register($name, $enabled, $metadata);
    }

    public static function has(string $name): bool
    {
        return self::registry()->has($name);
    }

    public static function enabled(string $name): bool
    {
        return self::registry()->enabled($name);
    }

    public static function get(string $name): ?CapabilityDefinition
    {
        return self::registry()->get($name);
    }

    public static function all(): array
    {
        return self::registry()->all();
    }

    public static function enabledCapabilities(): array
    {
        return self::registry()->enabledCapabilities();
    }

    private static function registry(): CapabilityRegistryContract
    {
        return app(CapabilityRegistryContract::class);
    }
}
