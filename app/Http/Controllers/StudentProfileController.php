<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        $studentSection = auth()->user()->student()->with('currentSection')->first();

        $achievements = Achievement::join('student_achievement', 'achievements.id', '=', 'student_achievement.achievement_id')
            ->where('student_achievement.student_id', $student->id)
            ->select('achievements.*')
            ->get();

        $leaderboard = Student::join('users', 'students.user_id', '=', 'users.id')
            ->orderByDesc('students.weekly_score')
            ->select('students.weekly_score', 'users.name', 'users.profile_photo')
            ->limit(10)
            ->get();

        $rank = Student::where('weekly_score', '>', (int) $student->weekly_score)->count() + 1;

        return view('student.profile.index', compact('user', 'student', 'achievements', 'leaderboard', 'rank'));
    }

    /**
     * Menampilkan Halaman Edit Profil Mahasiswa.
     */
    public function edit()
    {
        $user = Auth::user();
        $student = $user->student;

        return view('student.profile.edit', compact('user', 'student'));
    }

    /**
     * Update Profil oleh Mahasiswa.
     */
    public function update(Request $request)
    {
        $user = auth()->user(); 
        $student = $user->student;

        // Validasi Data
        $validatedData = $request->validate([
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|in:Islam,Protestan,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'phone_number' => 'nullable|string|max:20',
            'prodi' => 'nullable|string|in:Sistem Informasi Bisnis,Teknik Informatika',
            'semester' => 'nullable|integer|min:1|max:8',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
        ]);

        // ✅ **Jika Ada Upload Foto Baru**
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && $user->profile_photo !== 'profile_photos/default.webp') {
                Storage::delete('public/' . $user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');

            $user->profile_photo = $path;
        }

        // ✅ **Jika User Menghapus Foto**
        if ($request->input('delete_photo') == "1") {
            if ($user->profile_photo !== 'profile_photos/default.webp') {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = 'profile_photos/default.webp';
        }

        $user->save();
        $student->update($validatedData);

        return redirect()->route('student.profile.index')->with('success', 'Profile updated successfully!');
    }
}
