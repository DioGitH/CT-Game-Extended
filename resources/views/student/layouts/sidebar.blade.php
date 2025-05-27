<div id="sidebar"
    class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white h-screen fixed left-0 top-0 transition-all duration-500
        flex flex-col shadow-2xl border-r-4 border-blue-500 glow-sidebar">

    <!-- Logo dan Toggle Sidebar -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-blue-500 shadow-md">
        <!-- Logo -->
        <h2 id="sidebar-logo"
            class="text-2xl font-bold sidebar-text transition-all duration-300 tracking-wider flex items-center">
            <img src="{{ asset('storage/icons/game.png') }}" alt="Logo" class="w-10 h-10 mr-3">CTG
        </h2>

        <!-- Toggle Sidebar Button -->
        <button id="toggleSidebar" class="focus:outline-none hover:scale-110 transition-all hover-sfx"
            onclick="playShowDetailSound()">
            <span
                class="text-white text-2xl cursor-pointer hover:text-gray-300 transition transform hover:rotate-90">☰</span>
        </button>
    </div>


    <!-- Navigation -->
    <nav class="mt-6 flex-1 space-y-2">
        <a href="{{ route('student.profile.index') }}"
            class="flex items-center px-6 py-3 bg-transparent hover:bg-blue-600 rounded-md mx-4 transition-all duration-300 shadow-lg hover:shadow-blue-500 hover-sfx"
            onclick="playClick()">
            <span class="text-xl glow-icon">👤</span>
            <span class="ml-3 sidebar-text transition-all duration-300 font-semibold">Profile</span>
        </a>
        <a href="{{ route('student.mission.index') }}"
            class="flex items-center px-6 py-3 bg-transparent hover:bg-blue-600 rounded-md mx-4 transition-all duration-300 shadow-lg hover:shadow-blue-500 hover-sfx"
            onclick="playClick()">
            <span class="text-xl glow-icon">📜</span>
            <span class="ml-3 sidebar-text transition-all duration-300 font-semibold">Missions</span>
        </a>
        <a href="{{ route('student.tutorial.index') }}"
            class="flex items-center px-6 py-3 bg-transparent hover:bg-blue-600 rounded-md mx-4 transition-all duration-300 shadow-lg hover:shadow-blue-500 hover-sfx"
            onclick="playClick()">
            <span class="text-xl glow-icon">📖</span>
            <span class="ml-3 sidebar-text transition-all duration-300 font-semibold">Tutorial</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="px-6 py-4 border-t border-blue-500">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition-all duration-300 shadow-md hover:shadow-red-500">
                <span class="text-xl glow-icon">⏻</span>
                <span class="ml-3 sidebar-text transition-all duration-300 font-semibold">Logout</span>
            </button>
        </form>
    </div>
    <audio id="hover-sound" src="{{ asset('sfx/hover.mp3') }}"></audio>
    <audio id="click-sound" src="{{ asset('sfx/click.mp3') }}"></audio>
    <audio id="show-detail-sound" src="{{ asset('sfx/showdetailbox.mp3') }}"></audio>
</div>

<!-- CSS Gaming -->
<style>
    /* Efek Glowing Sidebar */
    .glow-sidebar {
        box-shadow: 0px 0px 20px rgba(0, 0, 255, 0.5);
    }

    /* Efek Glowing Ikon */
    .glow-icon {
        transition: all 0.3s ease-in-out;
    }

    .glow-icon:hover {
        text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.8);
    }
</style>
<script>
    function playShowDetailSound() {
        const audio = document.getElementById("show-detail-sound");
        if (audio) {
            audio.currentTime = 0;
            audio.play();
        }
    }

    function playClick() {
        const audio = document.getElementById("click-sound");
        if (audio) {
            audio.currentTime = 0;
            audio.play();
        }
    }

    function playHoverSound() {
        const audio = document.getElementById("hover-sound");
        if (audio) {
            audio.currentTime = 0;
            audio.play();
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const hoverElements = document.querySelectorAll(".hover-sfx");

        hoverElements.forEach(el => {
            el.addEventListener("mouseenter", playHoverSound);
        });
    });

    function rebindHoverSound() {
        const hoverElements = document.querySelectorAll(".hover-sfx");

        hoverElements.forEach(el => {
            el.removeEventListener("mouseenter", playHoverSound);
            el.addEventListener("mouseenter", playHoverSound);
        });
    }
</script>
