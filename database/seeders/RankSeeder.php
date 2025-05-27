<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rank;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            ['name' => 'Coder Cupu', 'min_exp' => 0, 'max_exp' => 29],
            ['name' => 'Anak Terminal', 'min_exp' => 30, 'max_exp' => 104],
            ['name' => 'Junior Debugger', 'min_exp' => 105, 'max_exp' => 314],
            ['name' => 'Tukang Debug', 'min_exp' => 315, 'max_exp' => 554],
            ['name' => 'Otak Logic', 'min_exp' => 555, 'max_exp' => 1004],
            ['name' => 'Logika Ninja', 'min_exp' => 1005, 'max_exp' => 1544],
            ['name' => 'Master Algoritma', 'min_exp' => 1545, 'max_exp' => 2084],
            ['name' => 'Arsitek Kode', 'min_exp' => 2085, 'max_exp' => 2624],
            ['name' => 'Dewa Ngoding', 'min_exp' => 2625, 'max_exp' => 3344],
            ['name' => 'Suhu Digital', 'min_exp' => 3345, 'max_exp' => 999999],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}
