<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleandPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'create account',
            'edit account',
            'delete account',
            'view account',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $siwa  = Role::firstOrCreate(['name' => 'siswa']);
        $guru  = Role::firstOrCreate(['name' => 'guru']);

        // Assign permission to role
        $admin->givePermissionTo(Permission::all());
        $guru->givePermissionTo(['edit account']);
    }
}
