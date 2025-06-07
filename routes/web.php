<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\LecturerStudentController;
use App\Http\Controllers\LecturerDashboardController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentQuestionController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('redirect.after.login');
    }
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::resource('students', AdminStudentController::class)->names([
        'index' => 'admin.students.index',
        'create' => 'admin.students.create',
        'store' => 'admin.students.store',
        'show' => 'admin.students.show',
        'edit' => 'admin.students.edit',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy',
    ]);
});

// STUDENT ROUTES
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::post('/student/dismiss-tutorial', function () {
        $user = Auth::user();
        $user->tutorial_viewed = true;
        $user->save();

        return redirect('/student/tutorial');
    })->name('student.dismiss.tutorial')->middleware(['auth', 'role:student']);
    Route::resource('profile', StudentProfileController::class)->names([
        'index' => 'student.profile.index',
    ])->except(['show']);
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
    Route::put('/profile/update', [StudentProfileController::class, 'update'])->name('student.profile.update');
    Route::resource('mission', MissionController::class)->names([
        'index' => 'student.mission.index',
        'show' => 'student.mission.showChallenge',
    ]);
    Route::get('/review/{challenge}/{attempt}', [StudentReviewController::class, 'show'])->name('student.review');
    Route::post('/student/start-challenge', [MissionController::class, 'startChallenge'])->name('student.start.challenge');
    Route::get('/tutorial', function () {
        return view('student.tutorial.index');
    })->name('student.tutorial.index');
    Route::get('/question/{challenge_id}', [StudentQuestionController::class, 'show'])->name('student.start.question');
    Route::get('/question/{challenge_id}/next', [StudentQuestionController::class, 'nextQuestion'])->name('student.next.question');
    Route::get('/question/{challenge_id}/resume', [StudentQuestionController::class, 'resumeQuestion'])->name('student.resume.question');
    Route::post('/question/check', [StudentQuestionController::class, 'checkAnswer'])->name('student.question.check');
    Route::post('/question/check-multiple', [StudentQuestionController::class, 'checkMultiple'])->name('student.question.checkMultiple');
    Route::post('/check-essay', [StudentQuestionController::class, 'checkEssayAnswer'])->name('student.check.essay');
    Route::get('/student/check-lives', [StudentQuestionController::class, 'checkLives'])->name('student.check.lives');
    Route::post('/update-lives', [StudentQuestionController::class, 'updateLives'])->name('student.update.lives');
    Route::get('/challenge/{challenge_id}/summary/{attempt_number}', [StudentQuestionController::class, 'challengeSummary'])->name('student.challenge.summary');
    Route::post('/challenge/summary/log-focus', [StudentQuestionController::class, 'saveLogFocus'])->name('student.challange.logFocus');
    Route::post('/student/question/exit', [StudentQuestionController::class, 'exitChallenge'])->name('student.question.exit');
    Route::get('/rank-up/{challenge_id}/{attempt_number}', function ($challenge_id, $attempt_number) {
        $student = auth()->user()->student;
        return view('student.rank_up', compact('student', 'challenge_id', 'attempt_number'));
    })->name('student.rank.up')->middleware('auth');
});


// LECTURER ROUTES
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->group(function () {
    Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('lecturer.dashboard');
    Route::resource('challenges', ChallengeController::class)->names([
        'index' => 'lecturer.challenges.index',
        'create' => 'lecturer.challenges.create',
        'store' => 'lecturer.challenges.store',
        'show' => 'lecturer.challenges.show',
        'edit' => 'lecturer.challenges.edit',
        'update' => 'lecturer.challenges.update',
        'destroy' => 'lecturer.challenges.destroy',
    ]);
    Route::resource('students', LecturerStudentController::class)->names([
        'index' => 'lecturer.students.index',
        'create' => 'lecturer.students.create',
        'store' => 'lecturer.students.store',
        'show' => 'lecturer.students.show',
        'edit' => 'lecturer.students.edit',
        'update' => 'lecturer.students.update',
        'destroy' => 'lecturer.students.destroy',
    ]);
    Route::get('/students/{student}/challenge/{challenge}/attempt/{attempt}', [LecturerStudentController::class, 'detailResult'])
        ->name('lecturer.students.detail_result');
    Route::resource('questions', QuestionController::class)->names([
        'index' => 'lecturer.questions.index',
        'create' => 'lecturer.questions.create',
        'store' => 'lecturer.questions.store',
        'show' => 'lecturer.questions.show',
        'edit' => 'lecturer.questions.edit',
        'update' => 'lecturer.questions.update',
        'destroy' => 'lecturer.questions.destroy',
    ]);
    Route::resource('sections', SectionController::class)->names([
        'index' => 'lecturer.sections.index',
        'create' => 'lecturer.sections.create',
        'store' => 'lecturer.sections.store',
        'show' => 'lecturer.sections.show',
        'edit' => 'lecturer.sections.edit',
        'update' => 'lecturer.sections.update',
        'destroy' => 'lecturer.sections.destroy',
    ]);
    Route::post('/sections/reorder', [SectionController::class, 'reorder'])->name('lecturer.sections.reorder');
});

require __DIR__ . '/auth.php';
Route::middleware('auth')->get('/redirect-after-login', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.users.index');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.profile.index');
    } elseif ($user->hasRole('lecturer')) {
        return redirect()->route('lecturer.dashboard');
    }

    return redirect('/');
})->name('redirect.after.login');
