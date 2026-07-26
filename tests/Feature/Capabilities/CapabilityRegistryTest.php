<?php

namespace Tests\Feature\Capabilities;

use App\Core\Capabilities\Contracts\CapabilityRegistryContract;
use App\Core\Capabilities\Registry\CapabilityRegistry;
use App\Core\Capabilities\Support\Capability;
use Tests\TestCase;

class CapabilityRegistryTest extends TestCase
{
    public function test_registry_loads_foundation_capability_configuration(): void
    {
        $registry = app(CapabilityRegistryContract::class);

        $this->assertTrue($registry->has('notifications'));
        $this->assertTrue($registry->has('media'));
        $this->assertTrue($registry->has('localization'));
        $this->assertTrue($registry->has('react-islands'));
        $this->assertFalse($registry->enabled('notifications'));
        $this->assertFalse($registry->enabled('react-islands'));
    }

    public function test_registry_can_register_capability_metadata(): void
    {
        $registry = new CapabilityRegistry;

        $definition = $registry->register('records', true, [
            'description' => 'Generic reusable record capability.',
        ]);

        $this->assertTrue($registry->has('records'));
        $this->assertTrue($registry->enabled('records'));
        $this->assertSame($definition, $registry->get('records'));
        $this->assertSame('Generic reusable record capability.', $registry->get('records')->metadata['description']);
    }

    public function test_enabled_capabilities_only_returns_enabled_entries(): void
    {
        $registry = new CapabilityRegistry([
            'enabled-entry' => true,
            'disabled-entry' => false,
        ]);

        $this->assertArrayHasKey('enabled-entry', $registry->enabledCapabilities());
        $this->assertArrayNotHasKey('disabled-entry', $registry->enabledCapabilities());
    }

    public function test_unknown_capability_is_handled_safely(): void
    {
        $registry = new CapabilityRegistry;

        $this->assertFalse($registry->has('unknown'));
        $this->assertFalse($registry->enabled('unknown'));
        $this->assertNull($registry->get('unknown'));
    }

    public function test_static_capability_api_resolves_the_application_registry(): void
    {
        Capability::register('workspace', true);

        $this->assertTrue(Capability::has('workspace'));
        $this->assertTrue(Capability::enabled('workspace'));
        $this->assertArrayHasKey('workspace', Capability::all());
    }
}
