<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

// The UserSeeder class is responsible for seeding the users table with initial data. 
// It creates several user records, including an admin, a marketing manager, a marketing coordinator, and a student. 
// Each user is assigned a specific role and, in some cases, associated with a faculty. 
// This seeder can be used to populate the database with default user accounts when setting up the application for the first time or during testing. 
// By running this seeder, we can ensure that there are predefined users available in the system, which can be useful for testing features that depend on user accounts, such as authentication, role-based access control, contribution submissions, and administrative functions. Each user is
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $computing = Faculty::where('name', 'Faculty of Computing')->first();
        $business = Faculty::where('name', 'Faculty of Business')->first();

        // Admin
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@uog.ac.uk',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('Admin');

        // Marketing Manager
        $manager = User::create([
            'name' => 'Marketing Manager',
            'email' => 'manager@uog.ac.uk',
            'password' => Hash::make('password123'),
        ]);
        $manager->assignRole('Marketing Manager');

        // Marketing Coordinator
        $coordinator = User::create([
            'name' => 'Computing Coordinator',
            'email' => 'coordinator@uog.ac.uk',
            'password' => Hash::make('password123'),
            'faculty_id' => $computing->id,
        ]);
        $coordinator->assignRole('Marketing Coordinator');

        // Student
        $student = User::create([
            'name' => 'John Student',
            'email' => 'student@uog.ac.uk',
            'password' => Hash::make('password123'),
            'faculty_id' => $computing->id,
        ]);
        $student->assignRole('Student');
    }
}