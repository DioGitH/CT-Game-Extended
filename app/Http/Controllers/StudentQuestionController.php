<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\ChallengeResult;
use App\Models\LogFocus;
use Carbon\Carbon;


class StudentQuestionController extends Controller
{
    public function show($challenge_id)
    {
        $user = auth()->user();

        // Ambil jumlah percobaan sebelumnya untuk challenge ini
        $lastAttempt = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->max('attempt_number');

        $newAttemptNumber = $lastAttempt ? $lastAttempt + 1 : 1;

        // Buat challenge result baru untuk attempt ini
        ChallengeResult::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge_id,
            'attempt_number' => $newAttemptNumber,
            'total_score' => 0,
            'total_exp' => 0,
            'correct_answer' => 0,
            'wrong_answer' => 0,
        ]);

        // Ambil semua pertanyaan dalam challenge, acak urutannya
        $questions = Question::where('challenge_id', $challenge_id)->inRandomOrder()->get();

        // Simpan daftar pertanyaan ke sesi agar urutannya tetap konsisten dalam satu sesi challenge
        session(['challenge_questions' => $questions->pluck('id')->toArray()]);
        session(['current_question_index' => 0]);
        session(['current_attempt' => $newAttemptNumber]);

        return redirect()->route('student.next.question', ['challenge_id' => $challenge_id]);
    }

    public function checkAnswer(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        $question = Question::findOrFail($request->question_id);
        $selectedAnswer = $request->selected_answer;
        $attemptNumber = session('current_attempt');

        // Untuk soal true/false, ambil jawaban benar dari tabel answers
        if ($question->type == 'true_false') {
            $correctAnswer = $question->answers()->where('is_correct', 1)->first();
            $isCorrect = strtolower(trim($selectedAnswer)) == strtolower(trim($correctAnswer->answer));
        } else {
            // Untuk multiple choice, cocokkan dengan kolom is_correct
            $answer = $question->answers()->find($selectedAnswer);
            $isCorrect = $answer ? $answer->is_correct : false;
        }

        // Simpan jawaban di StudentAnswer
        StudentAnswer::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'challenge_id' => $question->challenge_id,
            'attempt_number' => $attemptNumber,
            'selected_answer' => $selectedAnswer,
            'is_correct' => $isCorrect
        ]);

        // Update ChallengeResult
        $challengeResult = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $question->challenge_id)
            ->where('attempt_number', $attemptNumber)
            ->firstOrFail();

        if ($isCorrect) {
            $challengeResult->correct_answers += 1;
            $challengeResult->total_score += $question->score;
            $challengeResult->total_exp += $question->exp;
        } else {
            $challengeResult->wrong_answers += 1;
            $user->student->decrement('lives', 1);
        }
        $challengeResult->save();
        if ($student->lives < 5 && $student->next_life_at === null) {
            $student->next_life_at = now()->addMinutes(60);
            $student->save();
        }

        return response()->json([
            'is_correct' => $isCorrect,
            'total_score' => $challengeResult->total_score,
            'total_exp' => $challengeResult->total_exp,
            'correct_answers' => $challengeResult->correct_answers,
            'wrong_answers' => $challengeResult->wrong_answers,
            'lives' => $user->student->lives
        ]);
    }

    public function checkMultiple(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        $question = Question::findOrFail($request->question_id);
        $attemptNumber = session('current_attempt');

        $selectedAnswers = collect($request->selected_answers)->map(fn($id) => (int) $id)->sort()->values();

        $correctAnswers = $question->answers()
            ->where('is_correct', 1)
            ->pluck('id')
            ->sort()
            ->values();

        $isCorrect = $selectedAnswers->toArray() === $correctAnswers->toArray();

        foreach ($selectedAnswers as $answerId) {
            StudentAnswer::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'challenge_id' => $question->challenge_id,
                'attempt_number' => $attemptNumber,
                'selected_answer' => $answerId,
                'is_correct' => $isCorrect
            ]);
        }

        // Update challenge result
        $challengeResult = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $question->challenge_id)
            ->where('attempt_number', $attemptNumber)
            ->firstOrFail();

        if ($isCorrect) {
            $challengeResult->correct_answers += 1;
            $challengeResult->total_score += $question->score;
            $challengeResult->total_exp += $question->exp;
        } else {
            $challengeResult->wrong_answers += 1;
            $user->student->decrement('lives', 1);
        }

        $challengeResult->save();
        if ($student->lives < 5 && $student->next_life_at === null) {
            $student->next_life_at = now()->addMinutes(60);
            $student->save();
        }

        return response()->json([
            'correct' => $isCorrect,
            'total_score' => $challengeResult->total_score,
            'total_exp' => $challengeResult->total_exp,
            'correct_answers' => $challengeResult->correct_answers,
            'wrong_answers' => $challengeResult->wrong_answers,
            'lives' => $user->student->lives
        ]);
    }

    public function nextQuestion($challenge_id)
    {
        $questions = session('challenge_questions', []);
        $index = session('current_question_index', 0);
        $attemptNumber = session('current_attempt');
        $user = auth()->user();
        $student = $user->student;

        // Jika challenge selesai
        if ($index >= count($questions)) {
            $student->load('ranks');

            $challengeResult = ChallengeResult::where('user_id', $user->id)
                ->where('challenge_id', $challenge_id)
                ->where('attempt_number', $attemptNumber)
                ->first();

            if ($challengeResult) {
                $challengeResult->ended_at = now();
                $challengeResult->save();
                $previousHighest = ChallengeResult::where('user_id', $user->id)
                    ->where('challenge_id', $challenge_id)
                    ->where('attempt_number', '<', $attemptNumber)
                    ->max('total_score');

                if ($challengeResult->total_score > $previousHighest) {
                    $selisihScore = $challengeResult->total_score - $previousHighest;
                    $selisihExp = $challengeResult->total_exp;

                    $student->increment('weekly_score', $selisihScore);
                    $student->increment('total_score', $selisihScore);
                    $student->increment('exp', $selisihExp);
                }
                $rankUpdate = $student->updateRank();
                if ($rankUpdate['rank_changed']) {
                    return redirect()->route('student.rank.up', [
                        'challenge_id' => $challenge_id,
                        'attempt_number' => $attemptNumber
                    ]);
                }
            }


            // ** Cek & Update Streak **
            $today = now()->toDateString();
            $lastStreakDate = $student->last_played;

            if ($lastStreakDate === $today) {
                // Jika sudah menyelesaikan challenge hari ini, streak tetap
            } elseif ($lastStreakDate === now()->subDay()->toDateString()) {
                // Jika streak masih berlanjut dari hari sebelumnya, tambah streak
                $student->increment('streak');
            } else {
                // Jika streak terputus, reset streak ke 1
                $student->streak = 1;
            }

            // Simpan tanggal streak terbaru
            $student->last_played = $today;
            $student->save();
            return redirect()->route('student.challenge.summary', ['challenge_id' => $challenge_id, 'attempt_number' => $attemptNumber]);
        }

        $question = Question::find($questions[$index]);
        session(['current_question_index' => $index + 1]);

        // Hitung progress
        $progress = (($index + 1) / count($questions)) * 100;

        $question->answers = $question->answers()->inRandomOrder()->get();

        return view('student.question', compact('question', 'progress'));
    }


    public function checkEssayAnswer(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        $question = Question::findOrFail($request->question_id);
        $attemptNumber = session('current_attempt');
        $student->updateRank();

        $correctAnswer = $question->answers()->where('is_correct', 1)->first();

        if (!$correctAnswer) {
            return response()->json([
                'error' => 'No correct answer found for this question.'
            ], 400);
        }

        function normalizeText($text)
        {
            return preg_replace('/[^a-z0-9]/', '', strtolower($text));
        }
        function isSimilar($a, $b, $maxDistance = 2)
        {
            return levenshtein($a, $b) <= $maxDistance;
        }
        $submittedAnswer = normalizeText($request->answer);
        $correctAnswerText = normalizeText($correctAnswer->answer);

        $isCorrect = $submittedAnswer === $correctAnswerText || isSimilar($submittedAnswer, $correctAnswerText);

        StudentAnswer::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'challenge_id' => $question->challenge_id,
            'attempt_number' => $attemptNumber,
            'selected_answer' => $request->answer,
            'is_correct' => $isCorrect
        ]);

        // Update ChallengeResult
        $challengeResult = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $question->challenge_id)
            ->where('attempt_number', $attemptNumber)
            ->firstOrFail();

        if ($isCorrect) {
            $challengeResult->correct_answers += 1;
            $challengeResult->total_score += $question->score; // Skor per jawaban benar
            $challengeResult->total_exp += $question->exp; // XP per jawaban benar
        } else {
            $challengeResult->wrong_answers += 1;
            $user->student->decrement('lives', 1); // Kurangi nyawa jika salah
        }
        $challengeResult->save();
        if ($student->lives < 5 && $student->next_life_at === null) {
            $student->next_life_at = now()->addMinutes(60);
            $student->save();
        }

        return response()->json([
            'correct' => $isCorrect,
            'total_score' => $challengeResult->total_score,
            'total_exp' => $challengeResult->total_exp,
            'correct_answers' => $challengeResult->correct_answers,
            'wrong_answers' => $challengeResult->wrong_answers,
            'lives' => $user->student->lives
        ]);
    }

    public function challengeSummary($challenge_id, $attempt_number)
    {
        $totalQuestions = Question::where('challenge_id', $challenge_id)->count();

        $motivasiBenar = [
            "🔥 Menyalaa! Great job! You're improving!",
            "🚀 Gas terus, jangan kasih kendor!",
            "👏 Auto jago nih!",
            "💯 Kamu bukan kaleng-kaleng!",
            "🏆 Keren abangkuh!",
            "🧠 Big brain moment!",
            "🎉 Kamu layak dapet crown!",
            "🔝 Kamu top banget dah bro!"
        ];

        $motivasiSalah = [
            "💪 Learn & try again!",
            "😮‍💨 Gagal bukan akhir segalanya!",
            "🔄 Coba lagi, kamu pasti bisa!",
            "🤯 Jangan nyerah, bentar lagi bisa!",
            "📚 Belajar santai tapi konsisten!",
            "😎 Santai, proses adalah kunci!",
            "🔥 Skill dewa itu hasil latihan!",
            "🤙 Tenang, semua ada waktunya!"
        ];

        $user = auth()->user();
        $challengeResult = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->where('attempt_number', $attempt_number)
            ->firstOrFail();
        $isPerfect = $challengeResult->correct_answers == $totalQuestions;
        $user->student->updateRank();

        return view('student.challenge_summary', compact('challengeResult', 'motivasiBenar', 'motivasiSalah', 'isPerfect'));
    }

    public function saveLogFocus(Request $request)
    {
        $user = auth()->user();
        $challengeResult = ChallengeResult::findOrFail($request->challengeresult_id);

        try {
            foreach ($request->logs as $log) {
                LogFocus::create([
                    'user_id' => $user->id,
                    'challengeresult_id' => $challengeResult->id,
                    'duration' => $log['duration'],
                    'start_at' => Carbon::createFromFormat('Y-m-d\TH:i:s.u', $log['start']),
                    'end_at' => Carbon::createFromFormat('Y-m-d\TH:i:s.u', $log['end']),
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Gagal menyimpan log', 'error' => $e->getMessage()], 500);
        }
        return response()->noContent();
    }

    public function updateLives()
    {
        $student = auth()->user()->student;
        if ($student->lives > 0) {
            $student->decrement('lives');
        }
        return response()->json(['lives' => $student->lives]);
    }

    public function exitChallenge(Request $request)
    {
        $user = auth()->user();
        $challenge_id = $request->challenge_id;

        $latestAttempt = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->max('attempt_number');

        if (!$latestAttempt) {
            return response()->json(['success' => false, 'message' => 'Tidak ada attempt yang bisa dihapus.']);
        }

        $challengeResult = ChallengeResult::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->where('attempt_number', $latestAttempt)
            ->first();

        if ($challengeResult) {
            $challengeResult->delete();
        }

        StudentAnswer::where('user_id', $user->id)
            ->where('challenge_id', $challenge_id)
            ->where('attempt_number', $latestAttempt)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Challenge exited and progress deleted.']);
    }
    public function checkLives()
    {
        $user = auth()->user();
        $lives = $user->student->lives;

        return response()->json(['lives' => $lives, 'next_life_at' => $user->student->next_life_at]);
    }
}
