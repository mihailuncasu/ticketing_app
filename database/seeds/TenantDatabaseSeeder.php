<?php

use App\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->addRolesAndPermissions();
    }

    private function addRolesAndPermissions()
    {
        // create permissions for an admin
        $adminPermissions = collect(['create user', 'edit user', 'delete user'])->map(function ($name) {
            return Permission::create([
                'name' => $name,
                'display_name' => ucwords($name)
            ]);
        });
        // add admin role
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
        ]);
        $adminRole->givePermissionTo($adminPermissions);

        // add a default user role
        Role::create([
            'name' => 'regular user',
            'display_name' => 'Regular User',
        ]);
    }
}