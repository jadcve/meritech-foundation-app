<?php

namespace Database\Seeders;

use App\Core\Authorization\FoundationPermissions;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FoundationRoleSeeder extends Seeder
{
    public function run(): void
    {
        $registrar = app(PermissionRegistrar::class);

        Tenant::query()->where('is_active', true)->each(function (Tenant $tenant) use ($registrar): void {
            $registrar->setPermissionsTeamId($tenant->getKey());

            foreach (FoundationPermissions::rolePermissions() as $roleName => $permissions) {
                Role::findOrCreate($roleName, 'web')->syncPermissions($permissions);
            }
        });

        $registrar->setPermissionsTeamId(null);
        $registrar->forgetCachedPermissions();
    }
}
