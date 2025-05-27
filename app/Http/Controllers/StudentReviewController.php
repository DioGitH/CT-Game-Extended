<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\ChallengeResult;

class StudentReviewController extends Controller
{
    public function show($challenge_id, $attempt_number)
    {
        $user = auth()->user();

        $result = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->where('attempt_number', $attempt_number)
            ->firstOrFail();

        $answers = StudentAnswer::with('question', 'selectedAnswer')
            ->where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->where('attempt_number', $attempt_number)
            ->get()
            ->groupBy('question_id');

        return view('student.review', compact('result', 'answers'));
    }
}
