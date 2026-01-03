<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        /* -------------------------------------------------
         | CLEAR OLD DATA (FK SAFE)
         -------------------------------------------------*/
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        Permission::query()->delete();
        Role::query()->delete();

        /* -------------------------------------------------
         | PERMISSIONS
         -------------------------------------------------*/
        $permissions = [
            // Common
            'dashboard.view',
            'profile.view',
            'profile.update',
            'profile.delete',

            // Users
            'users.index',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles
            'roles.index',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Permissions
            'permissions.index',
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'permissions.assign',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /* -------------------------------------------------
         | ROLES
         -------------------------------------------------*/
        $adminRole    = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $assessorRole = Role::create(['name' => 'Assessor', 'guard_name' => 'web']);
        $traineeRole  = Role::create(['name' => 'Trainee', 'guard_name' => 'web']);

        /* -------------------------------------------------
         | ASSIGN PERMISSIONS
         -------------------------------------------------*/
        // Admin → ALL permissions
        $adminRole->syncPermissions(Permission::all());

        // Assessor → limited permissions
        $assessorRole->syncPermissions([
            'dashboard.view',
            'profile.view',
            'profile.update',
            'users.index',
            'users.view',
        ]);

        // Trainee → basic permissions
        $traineeRole->syncPermissions([
            'dashboard.view',
            'profile.view',
        ]);

        /* -------------------------------------------------
         | USERS
         -------------------------------------------------*/
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles('Admin');

        $assessor = User::updateOrCreate(
            ['email' => 'assessor@example.com'],
            [
                'name' => 'Assessor User',
                'password' => Hash::make('password'),
            ]
        );
        $assessor->syncRoles('Assessor');

        $trainee = User::updateOrCreate(
            ['email' => 'trainee@example.com'],
            [
                'name' => 'Trainee User',
                'password' => Hash::make('password'),
            ]
        );
        $trainee->syncRoles('Trainee');
    }
}
