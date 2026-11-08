<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_routes_are_hidden_when_public_registration_is_disabled(): void
    {
        config(['foundation.auth.public_registration' => false]);

        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'hidden@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'hidden@example.com']);
    }

    public function test_register_routes_open_when_public_registration_is_enabled(): void
    {
        config(['foundation.auth.public_registration' => true]);

        $this->post('/register', [
            'name' => 'Visible User',
            'email' => 'visible@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertNotNull(User::query()->where('email', 'visible@example.com')->first());
    }
}
