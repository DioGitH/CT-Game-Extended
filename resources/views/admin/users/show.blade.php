@extends('admin.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200 font-inter">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-2">
                👤 <span>User Details</span>
            </h2>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-300 ease-in-out">
                ⬅️ Back to List
            </a>
        </div>

        <!-- User Profile -->
        <div
            class="flex flex-col md:flex-row items-center md:items-start space-x-0 md:space-x-6 bg-gray-50 p-6 rounded-lg shadow-md">
            <div class="flex-shrink-0 relative">
                <img id="profilePhoto"
                    src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-avatar.png') }}"
                    alt="{{ $user->name }}"
                    class="w-40 h-40 object-cover rounded-full shadow-md border border-gray-300 cursor-pointer transition duration-300 hover:shadow-xl">
                <p class="text-sm text-gray-500 text-center mt-2">Click to enlarge</p>
            </div>
            <div class="mt-4 md:mt-0 space-y-2">
                <h3 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                <p class="text-gray-600"><strong>📧 Email:</strong> {{ $user->email }}</p>
                <p class="text-gray-600"><strong>🎭 Role:</strong>
                    <span class="bg-blue-200 text-blue-800 text-sm px-3 py-1 rounded-full shadow-sm font-medium">
                        {{ $user->roles->pluck('name')->join(', ') }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Student Information -->
        @if ($user->hasRole('student') && $user->student)
            <div class="mt-6">
                <h3 class="text-lg font-bold text-gray-700 mb-3">📚 Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                        <p class="text-gray-700"><strong>🆔 NIM:</strong> {{ $user->student->nim }}</p>
                        <p class="text-gray-700"><strong>🎂 Birth Date:</strong>
                            {{ \Carbon\Carbon::parse($user->student->birth_date)->format('d M Y') }}
                        </p>
                        <p class="text-gray-700"><strong>🧑‍🤝‍🧑 Gender:</strong> {{ ucfirst($user->student->gender) }}</p>
                        <p class="text-gray-700"><strong>🕌 Religion:</strong> {{ $user->student->religion }}</p>
                    </div>
                    <div class="bg-gray-100 p-5 rounded-lg shadow-md">
                        <p class="text-gray-700"><strong>📞 Phone:</strong> {{ $user->student->phone_number ?? '-' }}</p>
                        <p class="text-gray-700"><strong>🏠 Address:</strong> {{ $user->student->address }}</p>
                        <p class="text-gray-700"><strong>🎓 Program:</strong> {{ $user->student->prodi }}</p>
                        <p class="text-gray-700"><strong>🏫 Class:</strong> {{ $user->student->class }}</p>
                        <p class="text-gray-700"><strong>📆 Semester:</strong> {{ $user->student->semester }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.users.edit', $user->id) }}"
                class="flex items-center space-x-2 bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition duration-300 ease-in-out">
                ✏️ <span>Edit</span>
            </a>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf @method('DELETE')
                <button type="submit"
                    class="flex items-center space-x-2 bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition duration-300 ease-in-out">
                    🗑️ <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Pop-up Modal -->
    <div id="photoModal"
        class="fixed inset-0 bg-black bg-opacity-70 hidden flex justify-center items-center z-50 transition-opacity duration-300 ease-in-out">
        <div
            class="relative bg-white p-6 rounded-lg shadow-lg transform scale-95 transition-transform duration-300 ease-in-out">
            <!-- Close Button -->
            <button id="closeModal"
                class="absolute top-2 right-2 text-gray-600 text-3xl font-bold hover:text-gray-900 transition duration-200 ease-in-out">
                ✖
            </button>
            <!-- Image -->
            <img id="modalImage"
                class="max-w-full max-h-[85vh] rounded-lg shadow-lg transition-transform duration-300 ease-in-out scale-95">
        </div>
    </div>

    <script>
        const profilePhoto = document.getElementById('profilePhoto');
        const photoModal = document.getElementById('photoModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');

        profilePhoto.addEventListener('click', () => {
            modalImage.src = profilePhoto.src;
            photoModal.classList.remove('hidden');
            setTimeout(() => {
                photoModal.classList.remove('opacity-0');
                modalImage.classList.remove('scale-95');
            }, 10);
        });

        closeModal.addEventListener('click', closePhotoModal);
        photoModal.addEventListener('click', (e) => {
            if (e.target !== modalImage) {
                closePhotoModal();
            }
        });

        function closePhotoModal() {
            photoModal.classList.add('opacity-0');
            modalImage.classList.add('scale-95');
            setTimeout(() => {
                photoModal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
