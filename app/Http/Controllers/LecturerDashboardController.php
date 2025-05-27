<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rank;
use App\Models\Student;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        $rankStats = Rank::withCount('students')->get();

        $streakStats = Student::selectRaw('streak, COUNT(*) as total')
            ->groupBy('streak')
            ->orderBy('streak')
            ->get();

        $topStudents = Student::with(['user', 'ranks'])
            ->orderByDesc('total_score')
            ->take(5)
            ->get();

        return view('lecturer.dashboard', compact('rankStats', 'streakStats', 'topStudents'));
    }
}
