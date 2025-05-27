@extends('admin.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
                ✏️ <span>Edit Student</span>
            </h2>
            <a href="{{ route('admin.students.index') }}"
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
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo -->
            <div class="mb-6 flex flex-col items-center">
                <div class="relative">
                    <img id="profile-photo-preview"
                        src="{{ $student->user->profile_photo ? asset('storage/' . $student->user->profile_photo) : asset('images/default-avatar.png') }}"
                        class="w-24 h-24 rounded-full shadow border border-gray-300 mb-2 cursor-pointer" alt="Profile Photo"
                        onclick="openImagePopup(this.src)">

                    @if ($student->user->profile_photo && $student->user->profile_photo !== 'profile_photos/default.webp')
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
                    <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $student->user->email) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- NIM & Birth Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="nim" class="block text-gray-700 font-bold mb-2">NIM</label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim', $student->nim) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="birth_date" class="block text-gray-700 font-bold mb-2">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', $student->birth_date) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required>
                </div>
            </div>

            <!-- Gender & Religion -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-gray-700 font-bold mb-2">Gender</label>
                    <select name="gender" id="gender" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Laki-laki" {{ $student->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $student->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>
                <div>
                    <label for="religion" class="block text-gray-700 font-bold mb-2">Religion</label>
                    <select name="religion" id="religion" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Islam" {{ $student->religion == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Protestan" {{ $student->religion == 'Protestan' ? 'selected' : '' }}>Protestan
                        </option>
                        <option value="Katolik" {{ $student->religion == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ $student->religion == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ $student->religion == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ $student->religion == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>
            </div>

            <!-- Phone & Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', $student->phone_number) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="address" class="block text-gray-700 font-bold mb-2">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $student->address) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Prodi, Semester, Class (1 Baris - 3 Kolom) -->
            <div class="flex flex-col md:flex-row md:space-x-4 mt-4">
                <div class="w-full md:w-1/3">
                    <label for="prodi" class="block text-gray-700 font-bold mb-2">Program</label>
                    <select name="prodi" id="prodi" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Sistem Informasi Bisnis"
                            {{ $student->prodi == 'Sistem Informasi Bisnis' ? 'selected' : '' }}>Sistem Informasi Bisnis
                        </option>
                        <option value="Teknik Informatika"
                            {{ $student->prodi == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="semester" class="block text-gray-700 font-bold mb-2">Semester</label>
                    <input type="number" name="semester" id="semester"
                        value="{{ old('semester', $student->semester) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div class="w-full md:w-1/3">
                    <label for="class" class="block text-gray-700 font-bold mb-2">Class</label>
                    <input type="text" name="class" id="class" value="{{ old('class', $student->class) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- Lives, Level, EXP, Score -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label for="streak" class="block text-gray-700 font-bold mb-2">🔥 Streak</label>
                    <input type="number" name="streak" id="streak" value="{{ old('streak', $student->streak) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="lives" class="block text-gray-700 font-bold mb-2">❤️ Lives</label>
                    <input type="number" name="lives" id="lives" value="{{ old('lives', $student->lives) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="level" class="block text-gray-700 font-bold mb-2">🏆 Level</label>
                    <select name="level" id="level" class="w-full border-gray-300 rounded-md p-2">
                        <option value="Si Kecil" {{ $student->level == 'Si Kecil' ? 'selected' : '' }}>Si Kecil</option>
                        <option value="Siaga" {{ $student->level == 'Siaga' ? 'selected' : '' }}>Siaga</option>
                        <option value="Penggalang" {{ $student->level == 'Penggalang' ? 'selected' : '' }}>Penggalang
                        </option>
                        <option value="Penegak" {{ $student->level == 'Penegak' ? 'selected' : '' }}>Penegak</option>
                    </select>
                </div>
                <div>
                    <label for="exp" class="block text-gray-700 font-bold mb-2">💡 Exp</label>
                    <input type="number" name="exp" id="exp" value="{{ old('exp', $student->exp) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="weekly_score" class="block text-gray-700 font-bold mb-2">📅 Weekly Score</label>
                    <input type="number" name="weekly_score" id="weekly_score"
                        value="{{ old('weekly_score', $student->weekly_score) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="total_score" class="block text-gray-700 font-bold mb-2">🏆 Total Score</label>
                    <input type="number" name="total_score" id="total_score"
                        value="{{ old('total_score', $student->total_score) }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200">
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
        function removeProfilePhoto() {
            document.getElementById('profile-photo-preview').src = "{{ asset('storage/profile_photos/default.webp') }}";
            document.getElementById('delete-photo').value = "1";
        }
    </script>
@endsection
