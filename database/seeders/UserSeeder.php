<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

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