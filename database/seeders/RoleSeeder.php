<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

// The RoleSeeder class is responsible for seeding the roles table with initial data. 
// It creates several roles, including Admin, Marketing Manager, Marketing Coordinator, Student, and Guest. 
// This seeder can be used to populate the database with default roles when setting up the application for the first time or during testing. By running this seeder, we can ensure that there are predefined roles available in the system, which can be useful for testing features that depend on role-based access control, such as user permissions, contribution submissions, and administrative functions. Each role is created using
class RoleSeeder extends Seeder
{

    // Run the database seeds.
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Marketing Manager']);
        Role::firstOrCreate(['name' => 'Marketing Coordinator']);
        Role::firstOrCreate(['name' => 'Student']);
        Role::firstOrCreate(['name' => 'Guest']);
    }
}