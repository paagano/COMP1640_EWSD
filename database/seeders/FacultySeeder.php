<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

class FacultySeeder extends Seeder
{
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