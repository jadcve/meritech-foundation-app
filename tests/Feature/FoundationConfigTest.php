<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationConfigTest extends TestCase
{
    public function test_foundation_auth_configuration_documents_base_auth_stack(): void
    {
        $this->assertSame('laravel-breeze', config('foundation.auth.provider'));
        $this->assertSame('blade', config('foundation.auth.stack.views'));
        $this->assertSame('tailwind', config('foundation.auth.stack.css'));
        $this->assertSame('alpine', config('foundation.auth.stack.interactivity'));

        $this->assertTrue(config('foundation.auth.features.login'));
        $this->assertTrue(config('foundation.auth.features.logout'));
        $this->assertTrue(config('foundation.auth.features.password_reset'));
        $this->assertTrue(config('foundation.auth.features.password_update'));
        $this->assertTrue(config('foundation.auth.features.email_verification'));
        $this->assertTrue(config('foundation.auth.features.profile'));
        $this->assertTrue(config('foundation.auth.features.authenticated_routes'));
    }
}
