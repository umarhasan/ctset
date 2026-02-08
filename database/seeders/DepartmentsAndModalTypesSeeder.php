<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\ModalType;

class DepartmentsAndModalTypesSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------
        // 1️⃣ Departments
        // -----------------------------
        $departmentsData = [
            ['name' => 'Clinical Department', 'short_code' => 'CLIN', 'module_type' => 'C', 'description' => 'Clinical activities department', 'is_active' => true],
            ['name' => 'Scientific Meetings', 'short_code' => 'SCIM', 'module_type' => 'S', 'description' => 'Scientific Meetings department', 'is_active' => true],
            ['name' => 'MATCVS', 'short_code' => 'MATC', 'module_type' => 'M', 'description' => 'MATCVS department', 'is_active' => true],
            ['name' => 'Department/Unit', 'short_code' => 'DEPT', 'module_type' => 'D', 'description' => 'Department unit', 'is_active' => true],
            ['name' => 'Evaluation Forms', 'short_code' => 'EVAL', 'module_type' => 'E', 'description' => 'Evaluation forms', 'is_active' => true],
        ];

        $departments = [];
        foreach ($departmentsData as $dept) {
            $departments[] = Department::create($dept);
        }

        // -----------------------------
        // 2️⃣ Modal Types
        // -----------------------------
        $tables = [
            'cicu_ward_rounds' => 'CICU Ward Rounds',
            'grand_ward_rounds' => 'Grand Ward Rounds',
            'daily_ward_rounds' => 'Daily Ward Rounds',
            'dops_trainees' => 'DOPS Trainees',
            'scientific_meetings' => 'Scientific Meetings',
            'matcvs_meetings' => 'MATCVS Meetings',
            'department_meetings' => 'Department Meetings',
            'evaluations' => 'Evaluations'
        ];

        foreach ($departments as $dept) {
            foreach ($tables as $tableKey => $tableLabel) {
                for ($i = 1; $i <= 2; $i++) {
                    $name = "$tableLabel Type $i";
                    $short_code = strtoupper(substr($tableLabel,0,4) . $i); // Auto short code
                    ModalType::create([
                        'name' => $name,
                        'short_code' => $short_code,
                        'department_id' => $dept->id,
                        'table_name' => $tableKey,
                        'description' => 'Auto-generated sample modal type',
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}