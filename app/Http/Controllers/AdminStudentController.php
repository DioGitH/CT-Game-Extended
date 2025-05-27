<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminStudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $query = Student::query()->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*', 'users.name as user_name', 'users.email as user_email');

        // Search Query
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('students.prodi', 'like', "%{$search}%")
                    ->orWhere('students.class', 'like', "%{$search}%")
                    ->orWhere('students.semester', 'like', "%{$search}%");
            });
        }

        // Sorting Logic
        $sortField = $request->get('sort', 'students.created_at');
        $sortOrder = $request->get('order', 'desc');

        $allowedFields = ['user_name', 'user_email', 'prodi', 'class', 'semester', 'students.created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        // Pagination Handling
        $perPage = $request->get('perPage', 10);

        if ($perPage == 'all') {
            $students = $query->get();
            $pagination = false;
        } else {
            $students = $query->paginate($perPage);
            $pagination = true;
        }

        return view('admin.students.index', compact('students', 'sortField', 'sortOrder', 'perPage', 'pagination'));
    }



    /**
     * Display the specified student details.
     */
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }


    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validasi Input
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'profile_photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nim'               => 'required|string|unique:students,nim',
            'address'           => 'nullable|string',
            'birth_date'        => 'nullable|date',
            'religion'          => 'required|string|in:Islam,Protestan,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'gender'            => 'required|string|in:Male,Female',
            'phone_number'      => 'nullable|string|max:15',
            'prodi'             => 'required|string|in:Sistem Informasi Bisnis,Teknik Informatika',
            'semester'          => 'required|integer|min:1|max:8',
            'class'             => 'required|string|max:50',
        ]);

        // ✅ Simpan Foto Profil (Jika Ada)
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // ✅ Buat User Baru
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
        ]);

        // ✅ Assign Role Student
        $user->assignRole('student');

        // ✅ Simpan Data Student
        Student::create([
            'user_id'       => $user->id,
            'nim'           => $request->nim,
            'address'       => $request->address,
            'birth_date'    => $request->birth_date,
            'religion'      => $request->religion,
            'gender'        => $request->gender,
            'phone_number'  => $request->phone_number,
            'prodi'         => $request->prodi,
            'semester'      => $request->semester,
            'class'         => $request->class,
        ]);

        // ✅ Redirect dengan Pesan Sukses
        return redirect()->route('admin.students.index')->with('success', 'Student added successfully!');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nim' => 'required|string|unique:students,nim,' . $student->id,
            'birth_date' => 'nullable|date',
            'religion' => 'required|string',
            'gender' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'prodi' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'class' => 'nullable|string|max:50',
            'streak' => 'required|integer|min:0',
            'lives' => 'required|integer|min:0|max:5',
            'level' => 'required|string|in:Si Kecil,Siaga,Penggalang,Penegak',
            'exp' => 'required|integer|min:0',
            'weekly_score' => 'required|integer|min:0',
            'total_score' => 'required|integer|min:0',
            'current_challenge_id' => 'nullable|exists:challenges,id',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // Update User
        $user = $student->user;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Cek apakah user ingin menghapus foto profil
        if ($request->delete_photo == "1") {
            if ($user->profile_photo && $user->profile_photo !== 'profile_photos/default.webp') {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Set foto ke default.webp jika dihapus
            $user->update(['profile_photo' => 'profile_photos/default.webp']);
        } elseif ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada dan bukan default
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo) && $user->profile_photo !== 'profile_photos/default.webp') {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Simpan foto baru
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->update(['profile_photo' => $photoPath]);
        }


        // Update Student
        $student->update([
            'nim' => $request->nim,
            'birth_date' => $request->birth_date,
            'religion' => $request->religion,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'class' => $request->class,
            'streak' => $request->streak,
            'lives' => $request->lives,
            'level' => $request->level,
            'exp' => $request->exp,
            'weekly_score' => $request->weekly_score,
            'total_score' => $request->total_score,
            'current_challenge_id' => $request->current_challenge_id,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Cek apakah user memiliki profile_photo
        if ($student->user->profile_photo && Storage::disk('public')->exists($student->user->profile_photo)) {
            Storage::disk('public')->delete($student->user->profile_photo);
        }

        // Hapus User dan Student
        $student->user->delete();
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
    }
}
