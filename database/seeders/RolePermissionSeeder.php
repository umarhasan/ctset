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
            // Exam permissions
            'exams.index',
            'exams.create',
            'exams.edit',
            'exams.delete',
            'exams.view',
            // Exam Invite permissions
            'view-pending-exams',
            'send-invites',
            'view-sent-invites',
            'view-invited-students',
            'update-invitation-status',
            'trainee.invitations',
            // Exam Matrix permissions
            'exam_matrices.index',
            'exam_matrices.create',
            'exam_matrices.edit',
            'exam_matrices.delete',
            'exam_matrices.view',
            // Work Cloud permissions
            'work_clouds.index',
            'work_clouds.create',
            'work_clouds.edit',
            'work_clouds.delete',
            'work_clouds.view',
            // Master Data permissions
            'hospitals.index',
            'hospitals.create',
            'hospitals.edit',
            'hospitals.delete',
            'hospitals.view',
            // Semesters
            'semesters.index',
            'semesters.create',
            'semesters.edit',
            'semesters.delete',
            'semesters.view',
            // Subjects
            'subjects.index',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',
            'subjects.view',
            // Topics
            'topics.index',
            'topics.create',
            'topics.edit',
            'topics.delete',
            'topics.view',
            // Timetable Events
            'timetable-events.index',
            'timetable-events.create',
            'timetable-events.edit',
            'timetable-events.delete',
            'timetable-events.view',
            // Ads permissions
            'ads.index',
            'ads.create',
            'ads.edit',
            'ads.delete',
            'ads.view',
            // Assignments permissions
            'assignments.index',
            'assignments.create',
            'assignments.edit',
            'assignments.delete',
            'assignments.view',
            // Results permissions
            'results.index',
            'results.pending',
            'results.view',

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

            'profile.view',
            'profile.update',
            'users.index',
            'users.view',
        ]);

        // Trainee → basic permissions
        $traineeRole->syncPermissions([
            'profile.view',
            'trainee.invitations',
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
