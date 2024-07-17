<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'create entry']);
        Permission::create(['name' => 'read entry']);
        Permission::create(['name' => 'update entry']);
        Permission::create(['name' => 'delete entry']);
        Permission::create(['name' => 'update my entry']);
        Permission::create(['name' => 'delete my entry']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(['create entry', 'read entry', 'update entry', 'delete entry']);

        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo(['create entry', 'read entry', 'update my entry', 'delete my entry']);
    }
}
