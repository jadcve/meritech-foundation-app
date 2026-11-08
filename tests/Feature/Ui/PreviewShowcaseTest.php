<?php

namespace Tests\Feature\Ui;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Database\Seeders\FoundationPreviewSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PreviewShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_showcase_stays_hidden_unless_enabled(): void
    {
        config(['foundation.ui.showcase' => false]);

        $this->get('/ui/health')->assertNotFound();
    }

    public function test_showcase_renders_sample_screens_for_each_vertical(): void
    {
        config(['foundation.ui.showcase' => true]);

        $this->get('/ui/health')
            ->assertOk()
            ->assertSee('Andes Salud')
            ->assertSee('Pacientes hoy')
            ->assertSee('--foundation-color-accent: #1d4ed8', false);

        $this->get('/ui/services')
            ->assertOk()
            ->assertSee('Lumina Studio')
            ->assertSee('Citas de hoy');

        $this->get('/ui/mechanic/agenda')
            ->assertOk()
            ->assertSee('Agenda de taller')
            ->assertSee('Frenos delanteros');

        $this->get('/ui/health/personas?q=Camila')
            ->assertOk()
            ->assertSee('Camila Soto')
            ->assertDontSee('Luis Herrera');

        $this->get('/ui/health/personas?q=nadie-real')
            ->assertOk()
            ->assertSee('Sin coincidencias');

        $this->get('/ui/health/personas/camila-soto')
            ->assertOk()
            ->assertSee('Hemograma dentro de rango')
            ->assertSee('nota-muestra');

        $this->get('/ui/health/personas/no-existe')->assertNotFound();
        $this->get('/ui/desconocida')->assertNotFound();
    }

    public function test_preview_seed_creates_branded_tenants_and_owner_role(): void
    {
        $dispatcher = Model::getEventDispatcher();
        Model::unsetEventDispatcher();

        try {
            $this->seed(FoundationPreviewSeeder::class);
        } finally {
            Model::setEventDispatcher($dispatcher);
        }

        $user = User::query()->where('email', 'test@example.com')->first();
        $tenant = Tenant::query()->where('slug', 'andes-salud')->first();

        $this->assertNotNull($user);
        $this->assertNotNull($tenant);
        $this->assertSame(3, $user->tenantMemberships()->count());
        $this->assertSame(1, $user->tenantMemberships()->where('is_default', true)->count());
        $this->assertSame('#1d4ed8', $tenant->settings->primary_color);

        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->getKey());
        $this->assertTrue($user->fresh()->hasRole('owner'));
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
    }

    public function test_runtime_permission_package_is_not_a_dev_dependency(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $this->assertArrayHasKey('spatie/laravel-permission', $composer['require']);
        $this->assertArrayNotHasKey('spatie/laravel-permission', $composer['require-dev']);
    }
}
