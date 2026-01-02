<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Roles
        $admin = Role::create(['name' => 'Admin']);
        $assessor = Role::create(['name' => 'Assessor']);
        $trainee = Role::create(['name' => 'Trainee']);

        // Assign Permissions
        $admin->givePermissionTo(Permission::all());

        $assessor->givePermissionTo([
            'user-list',
        ]);
    }
}

