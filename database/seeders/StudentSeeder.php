<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;
use App\Models\Rank;
use League\Csv\Reader;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = base_path('storage/app/seeds/students.csv');
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            // Buat User baru
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'profile_photo' => 'profile_photos/default.webp',
                'password' => Hash::make($row['nim']),
            ]);
            $user->assignRole('student');

            $student = Student::create([
                'user_id' => $user->id,
                'nim' => $row['nim'],
                'address' => $row['address'],
                'birth_date' => $row['birth_date'],
                'religion' => $row['religion'],
                'gender' => $row['gender'],
                'phone_number' => $row['phone_number'],
                'prodi' => $row['prodi'],
                'semester' => $row['semester'],
                'class' => $row['class'],
                'exp' => 0,
            ]);

            $rank = Rank::where('min_exp', '<=', $student->exp)
                ->where('max_exp', '>=', $student->exp)
                ->first();

            if (!$rank) {
                $rank = Rank::orderBy('min_exp', 'asc')->first();
            }

            if ($rank) {
                $student->ranks()->attach($rank->id, ['received_at' => now()]);
            }
        }
    }
}
