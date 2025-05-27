<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Student;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Student::query()->update(['weekly_score' => 0]);
    info('✅ Monthly reset: weekly scores have been reset!');
})->monthlyOn(15, '00:00');

Schedule::call(function () {
    $students = Student::where('lives', '<', 5)
        ->where('next_life_at', '<=', now())
        ->get();

    foreach ($students as $student) {
        $student->lives += 1;

        if ($student->lives < 5) {
            $student->next_life_at = now()->addMinutes(60);
        } else {
            $student->next_life_at = null;
        }

        $student->save();
    }
})->everyMinute();
