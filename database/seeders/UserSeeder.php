<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'profile_photo' => 'profile_photos/default.webp',
            'email' => 'admin@ct.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ])->assignRole('admin');

        $lecturers = [
            [
                'name' => 'Lecturer One',
                'profile_photo' => 'profile_photos/default.webp',
                'email' => 'lecturer1@ct.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Lecturer Two',
                'profile_photo' => 'profile_photos/default.webp',
                'email' => 'lecturer2@ct.com',
                'password' => Hash::make('password'),
            ],
        ];
        foreach ($lecturers as $lecturer) {
            User::create($lecturer)->assignRole('lecturer');
        }
    }
}
