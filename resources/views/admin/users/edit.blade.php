@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-md max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">✏️ Edit User</h2>
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-200">
                ⬅️ Back to List
            </a>
        </div>

        <!-- Success & Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg shadow">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo -->
            <div class="mb-6 flex flex-col items-center">
                <div class="relative">
                    <img id="profile-photo-preview"
                        src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-avatar.png') }}"
                        class="w-24 h-24 rounded-full shadow border border-gray-300 mb-2" alt="Profile Photo">

                    @if ($user->profile_photo && $user->profile_photo !== 'profile_photos/default.webp')
                        <!-- Remove Photo Button -->
                        <button type="button" onclick="removeProfilePhoto()"
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-red-600">
                            ✖
                        </button>
                        <input type="hidden" name="delete_photo" id="delete-photo" value="0">
                    @endif
                </div>
                <label class="text-gray-700 font-semibold">Change Profile Photo:</label>
                <input type="file" name="profile_photo" id="profile_photo"
                    class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
            </div>

            <!-- Name & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-gray-700 font-bold mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Password (Leave blank to keep current)</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full border-gray-300 rounded-md p-2 pr-10 focus:ring focus:ring-blue-200">
                        <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                            onclick="togglePassword('password')">
                            👁️
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full border-gray-300 rounded-md p-2 pr-10 focus:ring focus:ring-blue-200">
                        <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer"
                            onclick="togglePassword('password_confirmation')">👁️</span>
                    </div>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="mb-6 mt-4">
                <label for="role" class="block text-gray-700 font-bold mb-2">User Role</label>
                <select name="role" id="role"
                    class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                    <option value="lecturer" {{ $user->hasRole('lecturer') ? 'selected' : '' }}>Lecturer</option>
                    <option value="student" {{ $user->hasRole('student') ? 'selected' : '' }}>Student</option>
                </select>
            </div>

            <!-- Student-Only Fields -->
            <div id="student-fields" class="{{ $user->hasRole('student') ? '' : 'hidden' }}">
                <h3 class="text-lg font-bold text-gray-700 mb-3">📚 Student Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nim" class="block text-gray-700 font-bold mb-2">NIM</label>
                        <input type="text" name="nim" id="nim" value="{{ old('nim', $user->student->nim ?? '') }}"
                            class="w-full border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label for="birth_date" class="block text-gray-700 font-bold mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', $user->student->birth_date ?? '') }}"
                            class="w-full border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="religion" class="block text-gray-700 font-bold mb-2">Religion</label>
                        <select name="religion" id="religion" class="w-full border-gray-300 rounded-md p-2">
                            <option value="Islam"
                                {{ old('religion', $user->student->religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Protestan"
                                {{ old('religion', $user->student->religion ?? '') == 'Protestan' ? 'selected' : '' }}>Protestan
                            </option>
                            <option value="Katolik"
                                {{ old('religion', $user->student->religion ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik
                            </option>
                            <option value="Hindu"
                                {{ old('religion', $user->student->religion ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha"
                                {{ old('religion', $user->student->religion ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha
                            </option>
                            <option value="Konghucu"
                                {{ old('religion', $user->student->religion ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu
                            </option>
                            <option value="Lainnya"
                                {{ old('religion', $user->student->religion ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="gender" class="block text-gray-700 font-bold mb-2">Gender</label>
                        <select name="gender" id="gender" class="w-full border-gray-300 rounded-md p-2">
                            <option value="Laki-laki"
                                {{ old('gender', $user->student->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan"
                                {{ old('gender', $user->student->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number"
                            value="{{ old('phone_number', $user->student->phone_number ?? '') }}"
                            class="w-full border-gray-300 rounded-md p-2">
                    </div>
                    <div>
                        <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
                        <input type="text" name="address" id="address"
                            value="{{ old('address', $user->student->address ?? '') }}"
                            class="w-full border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="prodi" class="block text-gray-700 font-bold mb-2">Program</label>
                        <select name="prodi" id="prodi" class="w-full border-gray-300 rounded-md p-2">
                            <option value="Sistem Informasi Bisnis"
                                {{ old('prodi', $user->student->prodi ?? '') == 'Sistem Informasi Bisnis' ? 'selected' : '' }}>
                                Sistem Informasi Bisnis</option>
                            <option value="Teknik Informatika"
                                {{ old('prodi', $user->student->prodi ?? '') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik
                                Informatika</option>
                        </select>
                    </div>
                    <div>
                        <label for="semester" class="block text-gray-700 font-bold mb-2">Semester</label>
                        <input type="number" name="semester" id="semester"
                            value="{{ old('semester', $user->student->semester ?? '') }}"
                            class="w-full border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-500 transition">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            let input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        function removeProfilePhoto() {
            document.getElementById('profile-photo-preview').src = "{{ asset('storage/profile_photos/default.webp') }}";
            document.getElementById('delete-photo').value = "1";
        }

        document.getElementById('role').addEventListener('change', function() {
            document.getElementById('student-fields').classList.toggle('hidden', this.value !== 'student');
        });
    </script>
@endsection
