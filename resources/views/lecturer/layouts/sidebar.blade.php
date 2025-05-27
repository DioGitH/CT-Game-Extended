<div class="w-64 h-screen bg-blue-900 text-white p-6 shadow-lg flex flex-col justify-between fixed left-0 top-0">
    <!-- Header -->
    <div>
        <a href="{{ route('lecturer.dashboard') }}"
            class="block text-3xl font-extrabold tracking-wide text-center mb-8 hover:underline hover:text-blue-300 transition duration-200">
            Lecturer Panel
        </a>

        <!-- Navigation -->
        <nav class="space-y-4">
            <a href="{{ route('lecturer.sections.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M3 10h18M3 15h18M3 20h18" />
                </svg>
                <span class="font-medium">Sections</span>
            </a>
            <a href="{{ route('lecturer.challenges.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                </svg>
                <span class="font-medium">Challenges</span>
            </a>
            <a href="{{ route('lecturer.questions.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <span class="font-medium">Questions</span>
            </a>
            <a href="{{ route('lecturer.students.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z" />
                </svg>
                <span class="font-medium">Students</span>
            </a>

        </nav>
    </div>

    <!-- Logout Button -->
    <form action="{{ route('logout') }}" method="POST" class="mt-8">
        @csrf
        <button type="submit"
            class="w-full flex items-center justify-center p-3 bg-red-600 rounded-lg hover:bg-red-500 transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-7.5A2.25 2.25 0 003.75 5.25v13.5A2.25 2.25 0 006 21h7.5a2.25 2.25 0 002.25-2.25V15M18 12h-9m0 0l3-3m-3 3l3 3" />
            </svg>
            <span class="font-semibold">Logout</span>
        </button>
    </form>
</div>
