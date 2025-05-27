@extends('admin.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200 font-inter">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
                🎓 <span>Student Details</span>
            </h2>
            <a href="{{ route('admin.students.index') }}"
                class="flex items-center bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-300 ease-in-out">
                ⬅️ Back to List
            </a>
        </div>

        <!-- Student Profile -->
        <div
            class="flex flex-col md:flex-row items-center md:items-start space-x-0 md:space-x-6 bg-gray-50 p-6 rounded-lg shadow-md">
            <div class="flex-shrink-0 relative">
                <img src="{{ $student->user->profile_photo ? asset('storage/' . $student->user->profile_photo) : asset('images/default-avatar.png') }}"
                    alt="{{ $student->user->name }}"
                    class="w-32 h-32 object-cover rounded-full shadow-md border border-gray-300 cursor-pointer transition duration-300 hover:shadow-xl"
                    onclick="openImagePopup(this.src)">
            </div>
            <div class="mt-4 md:mt-0 space-y-2">
                <h3 class="text-xl font-semibold text-gray-800">{{ $student->user->name }}</h3>
                <p class="text-gray-600"><strong>📧 Email:</strong> {{ $student->user->email }}</p>
                <p class="text-gray-600"><strong>🆔 NIM:</strong> {{ $student->nim }}</p>
                <p class="text-gray-600"><strong>🧑‍🤝‍🧑 Gender:</strong> {{ ucfirst($student->gender) }}</p>
                <p class="text-gray-600"><strong>🕌 Religion:</strong> {{ $student->religion }}</p>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-700 mb-3">📚 Academic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                    <p class="text-gray-700"><strong>🎓 Program:</strong> {{ $student->prodi }}</p>
                    <p class="text-gray-700"><strong>🏫 Class:</strong> {{ $student->class }}</p>
                    <p class="text-gray-700"><strong>📆 Semester:</strong> {{ $student->semester }}</p>
                </div>
                <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                    <p class="text-gray-700"><strong>🎂 Birth Date:</strong>
                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d M Y') }}</p>
                    <p class="text-gray-700"><strong>📞 Phone:</strong> {{ $student->phone_number ?? '-' }}</p>
                    <p class="text-gray-700"><strong>🏠 Address:</strong> {{ $student->address }}</p>
                </div>
            </div>
        </div>

        <!-- Gamification Information -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-700 mb-3">🎮 Gamification Stats</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                    <p class="text-gray-700"><strong>🔥 Streak:</strong> {{ $student->streak }} days</p>
                    <p class="text-gray-700"><strong>⏳ Last Played:</strong>
                        {{ $student->last_played ? \Carbon\Carbon::parse($student->last_played)->diffForHumans() : '-' }}
                    </p>
                    <p class="text-gray-700"><strong>💖 Lives:</strong>
                        @for ($i = 0; $i < $student->lives; $i++)
                            ❤️
                        @endfor
                    </p>
                </div>
                <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                    <p class="text-gray-700"><strong>🎯 Level:</strong> {{ $student->level }}</p>
                    <p class="text-gray-700"><strong>🌟 EXP:</strong> {{ $student->exp }}</p>
                    <p class="text-gray-700"><strong>🏆 Weekly Score:</strong> {{ $student->weekly_score }}</p>
                    <p class="text-gray-700"><strong>🏅 Total Score:</strong> {{ $student->total_score }}</p>
                </div>
            </div>
        </div>

        <!-- Current Challenge -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-700 mb-3">🏁 Current Challenge</h3>
            <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                <p class="text-gray-700"><strong>📌 Challenge Name:</strong>
                    {{ $student->currentChallenge ? $student->currentChallenge->name : 'No active challenge' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.students.edit', $student->id) }}"
                class="flex items-center space-x-2 bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition duration-300 ease-in-out">
                ✏️ <span>Edit</span>
            </a>
            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this student?');">
                @csrf @method('DELETE')
                <button type="submit"
                    class="flex items-center space-x-2 bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition duration-300 ease-in-out">
                    🗑️ <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Profile Photo Popup -->
    <div id="imagePopup"
        class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="relative bg-white p-6 rounded-lg shadow-lg transform scale-95 transition-transform duration-300 ease-in-out">
            <button class="absolute top-2 right-2 bg-red-500 text-white rounded-full px-3 py-1 hover:bg-red-600"
                onclick="closeImagePopup()">✖</button>
            <img id="popupImage" class="max-w-full max-h-[85vh] rounded-lg shadow-lg">
        </div>
    </div>

    <script>
        function openImagePopup(src) {
            document.getElementById("popupImage").src = src;
            document.getElementById("imagePopup").classList.remove("hidden");
        }

        function closeImagePopup() {
            document.getElementById("imagePopup").classList.add("hidden");
        }
    </script>
@endsection
