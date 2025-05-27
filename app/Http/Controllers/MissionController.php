<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Challenge;
use App\Models\StudentRank;
use App\Models\Rank;
use App\Models\ChallengeResult;

class MissionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $studentRank = StudentRank::where('student_id', $student->id)->first();

        $rank = $studentRank ? Rank::where('id', $studentRank->rank_id)->value('name') : 'Unranked';

        $sections = Section::with(['challenges' => function ($query) {
            $query->withCount('questions');
        }])->orderBy('order')->get();
        $allRanks = Rank::orderBy('min_exp')->get();

        return view('student.mission.index', compact('student', 'rank', 'sections', 'allRanks'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $challenge = Challenge::withCount('questions')->findOrFail($id);
        $latestAttemptNumber = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $id)
            ->max('attempt_number');

        $latestResult = null;
        $isPerfect = false;

        if ($latestAttemptNumber) {
            $latestResult = ChallengeResult::where('user_id', $user->id)
                ->where('challenge_id', $id)
                ->where('attempt_number', $latestAttemptNumber)
                ->first();

            $isPerfect = $latestResult->correct_answers == $challenge->questions_count;
        }

        return response()->json([
            'id' => $challenge->id,
            'title' => $challenge->title,
            'question_count' => $challenge->questions_count,
            'exp' => $challenge->total_exp,
            'score' => $challenge->total_score,
            'attempt_number' => $latestAttemptNumber ?? 0,
            'is_perfect' => $isPerfect,
        ]);
    }

    public function startChallenge(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        $challengeId = $request->input('challenge_id');

        if (!$student || !$challengeId) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $challenge = Challenge::find($challengeId);
        if (!$challenge) {
            return response()->json(['message' => 'Challenge not found'], 404);
        }

        $student->current_challenge_id = $challenge->id;
        $student->current_section_id = $challenge->section_id;
        $student->save();

        return response()->json(['message' => 'Challenge and section updated']);
    }

    public function checkLives()
    {
        $user = auth()->user();
        $lives = $user->student->lives;

        return response()->json(['lives' => $lives, 'next_life_at' => $user->student->next_life_at]);
    }
}
