<nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <!-- Logo / Title -->
    <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-8 h-8 text-blue-600">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
        </svg>
        <a href="{{ route('lecturer.dashboard') }}"
            class="text-2xl font-bold text-gray-800 hover:text-blue-600 transition duration-200">
            Dashboard
        </a>
    </div>

    <!-- User Info & Dropdown -->
    <div class="relative">
        <button id="userMenuButton" class="flex items-center space-x-3 focus:outline-none">
            <!-- Profile Photo -->
            <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/default-avatar.png') }}"
                alt="User Photo" class="w-10 h-10 object-cover rounded-full border-2 border-gray-300 shadow-sm">

            <span class="text-gray-700 font-medium hidden md:inline">Welcome, {{ Auth::user()->name }}</span>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-gray-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div id="userDropdown"
            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 overflow-hidden z-10 transition-all duration-200 ease-in-out opacity-0 scale-95">

            <!-- Profile Info -->
            <div class="px-4 py-3 border-b border-gray-200 text-center">
                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/default-avatar.png') }}"
                    alt="User Avatar" class="w-12 h-12 mx-auto rounded-full shadow-md mb-2">
                <p class="text-sm text-gray-600">Signed in as</p>
                <p class="font-semibold text-gray-900">{{ Auth::user()->email }}</p>
            </div>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition duration-150">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    const userMenuButton = document.getElementById('userMenuButton');
    const userDropdown = document.getElementById('userDropdown');

    userMenuButton.addEventListener('click', (event) => {
        event.stopPropagation();
        userDropdown.classList.toggle('hidden');
        userDropdown.classList.toggle('opacity-0');
        userDropdown.classList.toggle('scale-95');
    });

    document.addEventListener('click', function(event) {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add('hidden', 'opacity-0', 'scale-95');
        }
    });
</script>
