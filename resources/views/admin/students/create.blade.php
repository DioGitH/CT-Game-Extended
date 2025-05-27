@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">🎓 Add New Student</h2>
            <a href="{{ route('admin.students.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-200">
                ⬅️ Back to List
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
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
            onsubmit="validateForm(event)">
            @csrf

            <!-- Profile Photo -->
            <div class="flex items-center space-x-4">
                <label class="block text-gray-700 font-bold w-1/3">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/*" class="w-2/3 border-gray-300 rounded-md p-2">
            </div>

            <!-- Name & Email -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border-gray-300 rounded-md p-2">
                </div>
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

            <!-- NIM & Phone -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim') }}" required
                        class="w-full border-gray-300 rounded-md p-2">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                        class="w-full border-gray-300 rounded-md p-2">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-gray-700 font-bold">Address</label>
                <input type="text" name="address" value="{{ old('address') }}"
                    class="w-full border-gray-300 rounded-md p-2">
            </div>

            <!-- Birth Date -->
            <div>
                <label class="block text-gray-700 font-bold">Birth Date</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                    class="w-full border-gray-300 rounded-md p-2">
            </div>

            <!-- Religion & Gender -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold">Religion</label>
                    <select name="religion" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Islam">Islam</option>
                        <option value="Protestan">Protestan</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold">Gender</label>
                    <select name="gender" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Male">Laki-laki</option>
                        <option value="Female">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Program & Semester -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold">Program</label>
                    <select name="prodi" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Sistem Informasi Bisnis">Sistem Informasi Bisnis</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold">Semester</label>
                    <select name="semester" class="w-full border-gray-300 rounded-md p-2">
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-gray-700 font-bold">Class</label>
                <input type="text" name="class" value="{{ old('class') }}" required
                    class="w-full border-gray-300 rounded-md p-2">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
                    ✅ Save
                </button>
                <a href="{{ route('admin.students.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-200">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            let field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";
        }

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
    </script>
@endsection
