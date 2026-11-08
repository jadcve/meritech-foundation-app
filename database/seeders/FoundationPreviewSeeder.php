<?php

namespace Database\Seeders;

use App\Core\Authorization\FoundationPermissions;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Models\TenantMembership;
use App\Core\Tenancy\Models\TenantSettings;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FoundationPreviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Demo Foundation',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $tenants = [
            [
                'name' => 'Lumina Studio',
                'slug' => 'lumina-studio',
                'primary_color' => '#be185d',
                'secondary_color' => '#f59e0b',
                'timezone' => 'America/Santiago',
                'currency' => 'CLP',
                'is_default' => false,
            ],
            [
                'name' => 'Andes Salud',
                'slug' => 'andes-salud',
                'primary_color' => '#1d4ed8',
                'secondary_color' => '#0ea5e9',
                'timezone' => 'America/Santiago',
                'currency' => 'CLP',
                'is_default' => true,
            ],
            [
                'name' => 'Ruta Sur',
                'slug' => 'ruta-sur',
                'primary_color' => '#9a3412',
                'secondary_color' => '#65a30d',
                'timezone' => 'America/Caracas',
                'currency' => 'VES',
                'is_default' => false,
            ],
        ];

        foreach ($tenants as $definition) {
            $tenant = Tenant::query()->firstOrCreate(
                ['slug' => $definition['slug']],
                ['name' => $definition['name'], 'is_active' => true],
            );

            TenantSettings::query()->updateOrCreate(
                ['tenant_id' => $tenant->getKey()],
                [
                    'locale' => 'es',
                    'timezone' => $definition['timezone'],
                    'currency' => $definition['currency'],
                    'theme' => 'light',
                    'primary_color' => $definition['primary_color'],
                    'secondary_color' => $definition['secondary_color'],
                    'date_format' => 'd-m-Y',
                    'time_format' => 'H:i',
                ],
            );

            TenantMembership::query()->updateOrCreate(
                ['tenant_id' => $tenant->getKey(), 'user_id' => $user->getKey()],
                ['is_active' => true, 'is_default' => $definition['is_default']],
            );
        }

        $this->call(AuthorizationSeeder::class);

        $registrar = app(PermissionRegistrar::class);

        Tenant::query()->whereIn('slug', ['lumina-studio', 'andes-salud', 'ruta-sur'])->each(function (Tenant $tenant) use ($registrar, $user): void {
            $registrar->setPermissionsTeamId($tenant->getKey());
            $user->assignRole(Role::findOrCreate(FoundationPermissions::OWNER, 'web'));
            $user->unsetRelation('roles');
        });

        $registrar->setPermissionsTeamId(null);
        $registrar->forgetCachedPermissions();
    }
}
