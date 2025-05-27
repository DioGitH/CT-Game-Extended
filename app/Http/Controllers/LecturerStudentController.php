<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ChallengeResult;
use App\Models\StudentAnswer;
use App\Models\Challenge;

class LecturerStudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index()
    {
        $students = Student::with('user')->paginate(10); 
        return view('lecturer.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('lecturer.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|unique:students,nim',
            'rank' => 'required|string',
            'exp' => 'required|integer|min:0',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
        ]);
        $user->assignRole('student');

        // Create Student
        Student::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'rank' => $request->rank,
            'exp' => $request->exp,
        ]);

        return redirect()->route('lecturer.students.index')->with('success', 'Student added successfully!');
    }

    public function show(Student $student)
    {
        $student->load('user'); // pastikan relasi 'user' sudah diload

        $results = ChallengeResult::where('user_id', $student->user_id)
            ->orderBy('attempt_number')
            ->get();

        return view('lecturer.students.show', compact('student', 'results'));
    }

    public function detailResult(Student $student, Challenge $challenge, $attempt)
    {
        $answers = StudentAnswer::with(['question', 'selectedAnswer'])
            ->where('user_id', $student->user_id)
            ->where('challenge_id', $challenge->id)
            ->where('attempt_number', $attempt)
            ->get()
            ->groupBy('question_id');

        $result = ChallengeResult::where('user_id', $student->user_id)
            ->where('challenge_id', $challenge->id)
            ->where('attempt_number', $attempt)
            ->first();

        return view('lecturer.students.detail_result', compact('student', 'challenge', 'attempt', 'result', 'answers'));
    }
    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('lecturer.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nim' => 'required|string|unique:students,nim,' . $student->id,
            'rank' => 'required|string',
            'exp' => 'required|integer|min:0',
        ]);

        // Update User
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Student
        $student->update([
            'nim' => $request->nim,
            'rank' => $request->rank,
            'exp' => $request->exp,
        ]);

        return redirect()->route('lecturer.students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $student->user->delete(); // Delete related user
        $student->delete();       // Delete student

        return redirect()->route('lecturer.students.index')->with('success', 'Student deleted successfully!');
    }
}
