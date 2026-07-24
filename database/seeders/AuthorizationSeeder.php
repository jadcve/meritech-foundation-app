<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FoundationPermissionSeeder::class,
            FoundationRoleSeeder::class,
        ]);
    }
}
