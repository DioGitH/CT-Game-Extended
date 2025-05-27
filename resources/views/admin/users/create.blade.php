@extends('admin.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
                ➕ <span>Add New User</span>
            </h2>
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-200">
                ⬅️ Back to Users List
            </a>
        </div>
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Form -->
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
            onsubmit="validateForm(event)">
            @csrf

            <!-- Name & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">👤 Name</label>
                    <input type="text" name="name"
                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">📧 Email</label>
                    <input type="email" name="email"
                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- Profile Photo -->
            <div class="mt-4">
                <label class="block text-gray-700 font-bold mb-2">🖼️ Profile Photo</label>
                <input type="file" name="profile_photo"
                    class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring focus:ring-blue-200">
            </div>

            <!-- Password & Confirm Password -->
            <div class="grid grid-cols-2 gap-4">
                <div class="relative">
                    <label class="block text-gray-700 font-bold">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full border-gray-300 rounded-md p-2 pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                        onclick="togglePassword('password')">
                        👁️
                    </span>
                </div>
                <div class="relative">
                    <label class="block text-gray-700 font-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border-gray-300 rounded-md p-2 pr-10">
                    <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                        onclick="togglePassword('password_confirmation')">👁️</span>
                </div>
            </div>
            <div class="text-red-600 text-sm mt-1 hidden" id="password-warning">
                ⚠️ Passwords do not match!
            </div>

            <!-- Role Selection -->
            <div class="mt-4">
                <label class="block text-gray-700 font-bold mb-2">🛠️ Role</label>
                <select name="role" id="role"
                    class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring focus:ring-blue-200" required
                    onchange="toggleStudentFields()">
                    <option value="admin">Admin</option>
                    <option value="lecturer">Lecturer</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <!-- Student Fields (Hidden by Default) -->
            <div id="student-fields" class="hidden mt-6 bg-gray-100 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold text-gray-700 mb-4">🎓 Student Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">🆔 NIM</label>
                        <input type="text" name="nim" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">📍 Address</label>
                        <input type="text" name="address" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">🎂 Birth Date</label>
                        <input type="date" name="birth_date" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">🕌 Religion</label>
                        <select name="religion" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                            <option value="Islam">Islam</option>
                            <option value="Protestan">Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">🧑 Gender</label>
                        <select name="gender" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">📞 Phone Number</label>
                        <input type="text" name="phone_number" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">🎓 Program</label>
                        <select name="prodi" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                            <option value="Sistem Informasi Bisnis">Sistem Informasi Bisnis</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">📆 Semester</label>
                        <select name="semester" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700 font-bold mb-2">🏫 Class</label>
                    <input type="text" name="class" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
                    ✅ Create User
                </button>
            </div>
        </form>
    </div>

    <script>
        function validateForm(event) {
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('password_confirmation').value;
            let warningMessage = document.getElementById('password-warning');

            if (password !== confirmPassword) {
                event.preventDefault(); // Mencegah form dikirim jika password tidak cocok
                warningMessage.classList.remove('hidden');
                return false;
            } else {
                warningMessage.classList.add('hidden');
            }
        }

        function toggleStudentFields() {
            let role = document.getElementById("role").value;
            let studentFields = document.getElementById("student-fields");
            studentFields.style.display = role === "student" ? "block" : "none";
        }

        function togglePassword(id) {
            let field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";
        }
    </script>
@endsection
