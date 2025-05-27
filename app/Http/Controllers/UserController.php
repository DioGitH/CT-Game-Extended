<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $users = User::with('roles')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderByRaw("
            CASE 
                WHEN roles.name = 'admin' THEN 1
                WHEN roles.name = 'lecturer' THEN 2
                WHEN roles.name = 'student' THEN 3
                ELSE 4
            END
        ")
            ->select('users.*')
            ->paginate($perPage == 'all' ? User::count() : $perPage)
            ->appends(request()->query());

        return view('admin.users.index', compact('users', 'perPage'));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Cek apakah user memiliki role 'student'
        $student = null;
        if ($user->hasRole('student')) {
            $student = Student::where('user_id', $user->id)->first();
        }

        return view('admin.users.show', compact('user', 'student'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,lecturer,student',
            // Jika role adalah student, validasi tambahan
            'nim' => 'required_if:role,student|nullable|string|unique:students,nim',
            'address' => 'required_if:role,student|nullable|string|max:255',
            'birth_date' => 'required_if:role,student|nullable|date',
            'religion' => 'required_if:role,student|nullable|string|in:Islam,Protestan,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'gender' => 'required_if:role,student|nullable|string|in:Laki-laki,Perempuan',
            'phone_number' => 'required_if:role,student|nullable|string|max:15',
            'prodi' => 'required_if:role,student|nullable|string|in:Sistem Informasi Bisnis,Teknik Informatika',
            'semester' => 'required_if:role,student|nullable|integer|min:1|max:8',
            'class' => 'required_if:role,student|nullable|string|max:50',
        ]);

        // Simpan profile photo jika ada
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath, // Simpan path foto
        ]);

        // Tambahkan role ke user
        $user->assignRole($request->role);

        // Jika role adalah student, simpan data student
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'religion' => $request->religion,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'prodi' => $request->prodi,
                'semester' => $request->semester,
                'class' => $request->class,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User added successfully!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi Input
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,lecturer,student',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Perbarui Data User Jika Input Tidak Kosong
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Cek apakah user ingin menghapus foto profil
        if ($request->delete_photo == "1") {
            if ($user->profile_photo && $user->profile_photo !== 'profile_photos/default.webp') {
                Storage::disk('public')->delete($user->profile_photo);
            }
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

        // Simpan perubahan user
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // **Update Student Data Jika Role Student**
        if ($request->role === 'student') {
            // Validasi tambahan untuk student
            $studentData = $request->validate([
                'nim' => 'nullable|string|unique:students,nim,' . optional($user->student)->id,
                'birth_date' => 'nullable|date',
                'religion' => 'nullable|string',
                'gender' => 'nullable|string|in:Laki-laki,Perempuan',
                'phone_number' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'prodi' => 'nullable|string|in:Sistem Informasi Bisnis,Teknik Informatika',
                'semester' => 'nullable|integer|min:1|max:8',
                'class' => 'nullable|string|max:10',
            ]);

            // Jika mahasiswa sudah ada, update datanya
            if ($user->student) {
                $user->student->update($studentData);
            } else {
                // Jika mahasiswa belum ada, buat baru
                $user->student()->create($studentData);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }


    public function destroy(User $user)
    {
        // Cek apakah user memiliki profile_photo
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Hapus user dari database
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
