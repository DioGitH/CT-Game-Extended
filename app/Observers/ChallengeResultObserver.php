<?php

namespace App\Observers;

use App\Models\ChallengeResult;
use App\Models\Achievement;
use Illuminate\Support\Facades\Log;

class ChallengeResultObserver
{
    public function saved(ChallengeResult $result)
    {
        $user = $result->user;
        $student = $user->student()->with('achievements')->first();

        $firstTime = ChallengeResult::where('user_id', $user->id)->count() === 1;
        self::unlockIf($student, 'first_challenge', $firstTime);

        $totalCompleted = ChallengeResult::where('user_id', $user->id)->count();
        self::unlockIf($student, 'five_challenges', $totalCompleted >= 5);

        $result->load('challenge');
        $isPerfect = $result->total_score === $result->challenge?->total_score;
        self::unlockIf($student, 'perfect_score', $isPerfect);
        Log::info('Score check', [
            'user_id' => $user->id,
            'total_score' => $result->total_score,
            'max_score' => $result->challenge?->total_score,
            'isPerfect' => $result->total_score === $result->challenge?->total_score
        ]);
    }

    private static function unlockIf($student, $achievementCode, $condition)
    {
        if (!$condition) return;

        $achievement = Achievement::where('code', $achievementCode)->first();
        if ($achievement && !$student->achievements->contains($achievement->id)) {
            $student->achievements()->attach($achievement->id, [
                'unlocked_at' => now(),
            ]);
        }
    }
}
