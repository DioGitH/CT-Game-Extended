<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class ResetWeeklyScore extends Command
{
    protected $signature = 'score:reset-weekly';
    protected $description = 'Reset weekly score for all students';

    public function handle()
    {
        Student::query()->update(['weekly_score' => 0]);
        $this->info('Weekly scores have been reset!');
    }
}
