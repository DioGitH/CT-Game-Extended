<div class="w-64 h-screen bg-blue-900 text-white p-6 shadow-lg flex flex-col justify-between fixed left-0 top-0">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold tracking-wide text-center mb-8">Admin Panel</h1>

        <!-- Navigation -->
        <nav class="space-y-4">
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A4.992 4.992 0 015 15V9a4.992 4.992 0 01.879-2.804M9 5h6m-3 10v6m-4-6v6m8-6v6">
                    </path>
                </svg>
                <span class="font-medium">Users</span>
            </a>

            <a href="{{ route('admin.students.index') }}"
                class="flex items-center p-3 bg-blue-700 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                <svg class="w-5 h-5 mr-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 7V9m0 12l-3-3m3 3l3-3"></path>
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
            <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-7.5A2.25 2.25 0 003.75 5.25v13.5A2.25 2.25 0 006 21h7.5a2.25 2.25 0 002.25-2.25V15M18 12h-9m0 0l3-3m-3 3l3 3">
                </path>
            </svg>
            <span class="font-semibold">Logout</span>
        </button>
    </form>
</div>
