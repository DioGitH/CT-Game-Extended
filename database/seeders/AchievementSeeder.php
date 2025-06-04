<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('achievements')->insert([
            [
                'code' => 'first_challenge',
                'name' => 'First Challenge Completed',
                'description' => 'You completed your first challenge!',
                'icon' => 'icons/first_challenge.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'five_challenges',
                'name' => 'Five Times the Charm',
                'description' => 'Completed 5 challenges!',
                'icon' => 'icons/five_challenges.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'perfect_score',
                'name' => 'Flawless Victory',
                'description' => 'Achieved a perfect score on a challenge.',
                'icon' => 'icons/perfect_score.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
