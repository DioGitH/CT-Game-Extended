@extends('student.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-gradient-to-br from-gray-900 to-gray-800 shadow-2xl rounded-xl text-white">
        <h2 class="text-3xl font-extrabold text-yellow-400 text-center drop-shadow-md">🛠 Edit Profile</h2>

        <!-- Form Edit Profile -->
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="flex flex-col items-center mt-6">
                <div class="relative w-32 h-32">
                    <img id="profile-preview"
                        src="{{ asset('storage/' . ($student->user->profile_photo ?? 'profile_photos/default.webp')) }}"
                        class="w-32 h-32 rounded-full border-4 border-yellow-400 shadow-lg transition transform hover:scale-110">

                    <!-- Tombol Hapus Foto (Jika bukan default) -->
                    @if ($student->user->profile_photo !== 'profile_photos/default.webp')
                        <button type="button" onclick="deleteProfilePhoto()"
                            class="absolute bottom-2 right-2 bg-red-600 text-white px-3 py-1 rounded-full shadow-md hover:bg-red-500 transition">
                            🗑️
                        </button>
                    @endif
                </div>

                <!-- Upload Foto -->
                <label for="profile_photo"
                    class="mt-4 bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-full shadow-md cursor-pointer hover:bg-yellow-400 transition hover:scale-105">
                    📷 Change Photo
                </label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden">
            </div>

            <!-- Hidden Input untuk Hapus Foto -->
            <input type="hidden" id="delete_photo" name="delete_photo" value="0">

            <!-- Grid Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIM (Read-Only) -->
                <div>
                    <label class="text-yellow-300 font-semibold">NIM</label>
                    <input type="text" value="{{ $student->nim }}" readonly
                        class="w-full p-3 bg-gray-700 rounded-lg border border-gray-500 text-white cursor-not-allowed">
                </div>

                <!-- Address -->
                <div>
                    <label class="text-yellow-300 font-semibold">🏠 Address</label>
                    <input type="text" name="address" value="{{ old('address', $student->address) }}"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                </div>

                <!-- Birth Date -->
                <div>
                    <label class="text-yellow-300 font-semibold">🎂 Birth Date</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                </div>

                <!-- Religion -->
                <div>
                    <label class="text-yellow-300 font-semibold">🙏 Religion</label>
                    <select name="religion"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                        @foreach (['Islam', 'Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $religion)
                            <option value="{{ $religion }}"
                                {{ old('religion', $student->religion) == $religion ? 'selected' : '' }}>
                                {{ $religion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label class="text-yellow-300 font-semibold">⚤ Gender</label>
                    <select name="gender"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                        <option value="Laki-laki" {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="text-yellow-300 font-semibold">📞 Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                </div>

                <!-- Prodi -->
                <div>
                    <label class="text-yellow-300 font-semibold">🎓 Program Studi</label>
                    <select name="prodi"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                        <option value="Sistem Informasi Bisnis"
                            {{ old('prodi', $student->prodi) == 'Sistem Informasi Bisnis' ? 'selected' : '' }}>
                            Sistem Informasi Bisnis
                        </option>
                        <option value="Teknik Informatika"
                            {{ old('prodi', $student->prodi) == 'Teknik Informatika' ? 'selected' : '' }}>
                            Teknik Informatika
                        </option>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="text-yellow-300 font-semibold">📅 Semester</label>
                    <select name="semester"
                        class="w-full p-3 rounded-lg border border-gray-500 bg-gray-700 text-white focus:ring-2 focus:ring-yellow-400 transition">
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}"
                                {{ old('semester', $student->semester) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="bg-yellow-400 text-gray-900 font-bold px-6 py-3 rounded-lg shadow-lg hover:bg-yellow-300 hover:scale-105 transition transform">
                    💾 Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for Profile Picture Handling -->
    <script>
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        function deleteProfilePhoto() {
            if (confirm("Are you sure you want to delete your profile photo?")) {
                document.getElementById('delete_photo').value = "1";
                document.getElementById('profile-preview').src = "{{ asset('storage/profile_photos/default.webp') }}";
            }
        }
    </script>
@endsection
