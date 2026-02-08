<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call RolePermissionSeeder
        // $this->call(RolePermissionSeeder::class);
        $this->call(DepartmentsAndModalTypesSeeder::class);
        // Agar baad me aur seeders add karni hain, unhe yahan call karo
        // $this->call(AnotherSeeder::class);
    }
}
