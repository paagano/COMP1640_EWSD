<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// The DatabaseSeeder class is responsible for seeding the application's database with initial data. 
// It serves as the main entry point for all database seeders and allows you to organize and run multiple seeders in a specific order. 
// In this implementation, the run method calls several other seeders, including RoleSeeder, FacultySeeder, AcademicYearSeeder, and UserSeeder. 
// Each of these seeders is responsible for populating specific tables in the database with predefined data, such as user roles, faculty information, academic years, and user accounts. By running the DatabaseSeeder, you can quickly set up the database with essential data for testing or initial deployment of the application, ensuring that the system
class DatabaseSeeder extends Seeder
{

    // Seed the application's database.
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            FacultySeeder::class,
            AcademicYearSeeder::class,
            UserSeeder::class,
        ]);
    }
}