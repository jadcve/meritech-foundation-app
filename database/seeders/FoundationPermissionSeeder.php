<?php

namespace Database\Seeders;

use App\Core\Authorization\FoundationPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class FoundationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (FoundationPermissions::permissions() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
