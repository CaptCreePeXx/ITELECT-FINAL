<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('captcreepex1'), // known password
            'role' => 'admin',
        ]);

        // Create a Dentist
        User::factory()->create([
            'name' => 'Dentist User',
            'email' => 'dentist@example.com',
            'password' => bcrypt('captcreepex1'),
            'role' => 'dentist',
        ]);

        // Create a Test Patient
        User::factory()->create([
            'name' => 'Patient User',
            'email' => 'test@example.com',
            'password' => bcrypt('captcreepex1'),
            'role' => 'patient',
        ]);

        // Optional: create a Receptionist
        User::factory()->create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@example.com',
            'password' => bcrypt('captcreepex1'),
            'role' => 'receptionist',
        ]);
    }
}

