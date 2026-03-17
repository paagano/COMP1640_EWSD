<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

// The FacultySeeder class is responsible for seeding the faculties table with initial data. 
// It creates several faculty records, each with a name, guest email, and guest password. 
// This seeder can be used to populate the database with default faculty information when setting up the application for the first time or during testing. 
// By running this seeder, we can ensure that there are predefined faculties available in the system, which can be useful for testing features that depend on faculty data, such as user registration, contribution submissions, and role assignments. Each faculty is assigned a guest email and a hashed password, allowing for easy access to the system using these credentials for testing purposes.
class FacultySeeder extends Seeder
{
    // Run the database seeds.
    public function run(): void
    {
        Faculty::create([
            'name' => 'Faculty of Computing',
            'guest_email' => 'guest.computing@uog.ac.uk',
            'guest_password' => Hash::make('password123'),
        ]);

        Faculty::create([
            'name' => 'Faculty of Business',
            'guest_email' => 'guest.business@uog.ac.uk',
            'guest_password' => Hash::make('password123'),
        ]);

        Faculty::create([
            'name' => 'Faculty of Engineering',
            'guest_email' => 'guest.engineering@uog.ac.uk',
            'guest_password' => Hash::make('password123'),
        ]);
    }
}