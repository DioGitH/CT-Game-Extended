@extends('student.layouts.app')

@section('content')
    <div class="animate-fadeIn">
        <div class="max-w-6xl mx-auto grid grid-cols-3 gap-6 text-gray-900">
            <!-- Profile -->
            <div
                class="bg-gradient-to-b from-blue-500 to-blue-400 p-6 rounded-2xl shadow-lg flex flex-col items-center col-span-2 border border-blue-400 transition hover:scale-105 hover-sfx">
                <div class="relative w-32 h-32">
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                        class="w-32 h-32 rounded-full border-4 border-yellow-400 shadow-lg transition hover:scale-110"
                        alt="Profile Photo">
                </div>
                <h2 class="text-2xl font-extrabold mt-4 text-white tracking-wider drop-shadow-lg">{{ $user->name }}</h2>
                <p class="text-gray-200">User {{ $user->id }}</p>
                <a href="{{ route('student.profile.edit', $student->id) }}"
                    class="bg-yellow-400 text-gray-900 px-6 py-2 mt-3 rounded-full shadow-lg hover:bg-yellow-300 transition hover:scale-105 text-center block hover-sfx"
                    onclick="playClick();">
                    Edit Profile
                </a>
            </div>

            <!-- Achievements -->
            <div
                class="bg-gradient-to-b from-purple-500 to-purple-400 p-6 rounded-2xl shadow-lg border border-purple-400 transition hover:scale-105 hover-sfx">
                <h3 class="text-xl font-extrabold mb-3 text-center text-white uppercase tracking-wider">🏆 Achievements</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($allAchievements as $achievement)
                        @php
                            $pivotData = $student->achievements->firstWhere('id', $achievement->id)?->pivot;
                            $isUnlocked = !is_null($pivotData);
                            $unlockedAt = $pivotData?->unlocked_at
                                ? \Carbon\Carbon::parse($pivotData->unlocked_at)->format('F j, Y \a\t H:i')
                                : null;
                        @endphp

                        <div onclick="showModal(
            '{{ asset('storage/' . $achievement->icon) }}',
            '{{ $achievement->name }}',
            '{{ $achievement->description }}',
            {{ $isUnlocked ? 'true' : 'false' }},
            '{{ $unlockedAt ?? '' }}'
        )"
                            class="flex flex-col items-center p-3 rounded-xl shadow-md border transition hover:scale-110 cursor-pointer
        {{ $isUnlocked ? 'bg-white border-purple-300' : 'bg-gray-100 border-gray-300 opacity-50 grayscale' }}">
                            <img src="{{ asset('storage/' . $achievement->icon) }}" class="w-14 h-14">
                        </div>
                    @endforeach


                </div>
                <a href="#" class="text-white mt-3 text-center block font-semibold hover:underline">See More</a>
            </div>

            <!-- Achievement Modal -->
            <div id="achievementModal"
                class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50 transition duration-300 ease-out">
                <div
                    class="relative bg-gradient-to-br from-purple-100 to-white p-6 rounded-2xl shadow-2xl w-[90%] max-w-md border border-purple-200 scale-100 animate-fadeIn">

                    <!-- Close Button -->
                    <button onclick="closeModal()"
                        class="absolute -top-3 -right-3 bg-white rounded-full shadow-md w-8 h-8 flex items-center justify-center text-purple-500 hover:bg-purple-100 hover:text-red-500 transition text-lg font-bold">
                        &times;
                    </button>

                    <!-- Achievement Icon -->
                    <div class="flex justify-center">
                        <div
                            class="w-24 h-24 bg-white rounded-full shadow-inner border-4 border-purple-300 flex items-center justify-center overflow-hidden">
                            <img id="modalIcon" src="" alt="Achievement Icon" class="w-20 h-20 object-contain">
                        </div>
                    </div>

                    <!-- Text Content -->
                    <h4 id="modalName"
                        class="text-xl font-extrabold text-purple-800 mt-4 tracking-wide uppercase text-center"></h4>
                    <p id="modalDescription" class="text-gray-700 text-sm mt-2 px-2 leading-relaxed text-center"></p>

                    <div id="modalStatus"
                        class="mt-4 px-4 py-2 text-xs rounded-full inline-block
               font-medium tracking-wide
               bg-purple-200 text-purple-700">
                    </div>
                </div>
            </div>

            <!-- Current Challenge & XP -->
            <div class="col-span-2 grid grid-cols-2 gap-6">
                <!-- Current Challenge -->
                <div
                    class="p-5 flex flex-col items-center justify-center bg-white rounded-xl shadow-lg border border-yellow-400 transition hover:scale-105 hover-sfx">
                    <h3 class="text-xl font-extrabold text-yellow-500">📖 Current Challenge</h3>
                    <div class="flex items-center justify-center mt-3">
                        <span class="text-3xl">🏆</span>
                        <p class="ml-1 font-semibold text-yellow-500">Section {{ $student->currentSection?->name ?? 'X' }}
                            -
                            {{ $student->current_challenge_id ?? 'X' }}</p>
                    </div>
                    <p class="text-gray-500 text-sm text-center mt-2">
                        Level Up! 🏅 Keep striving for greatness!
                    </p>
                </div>

                <!-- XP & Level -->
                <div
                    class="p-5 flex flex-col items-center justify-center bg-white rounded-xl shadow-lg border border-green-500 transition hover:scale-105 hover-sfx">
                    <h3 class="text-xl font-extrabold text-green-500">⚡ XP & Rank</h3>

                    @php
                        $minExp = $student->current_rank?->min_exp ?? 0;
                        $maxExp = $student->current_rank?->max_exp ?? 100;
                        $currentExp = $student->exp;
                        $expInCurrentRank = $currentExp - $minExp;
                        $range = $maxExp - $minExp;
                        $progressPercent = $range > 0 ? ($expInCurrentRank / $range) * 100 : 0;
                    @endphp

                    <p class="mt-2 text-gray-500 font-medium text-center">
                        XP: {{ $student->exp }} / {{ $student->current_rank?->max_exp }}
                    </p>

                    <div
                        class="w-full bg-gray-300 rounded-full h-5 mt-2 relative max-w-xs border border-green-400 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-400 to-green-500 h-5 rounded-full transition-all duration-700 animate-pulse"
                            style="width: {{ $progressPercent }}%">
                        </div>
                    </div>

                    <p class="mt-2 text-green-500 font-semibold text-center">
                        Rank: {{ $student->current_rank?->name ?? 'Unknown' }}
                    </p>
                </div>

            </div>

            <!-- Leaderboard -->
            <div
                class="bg-gradient-to-b from-blue-500 to-blue-400 p-6 rounded-2xl shadow-lg row-span-2 h-full flex flex-col border border-blue-400 transition hover:scale-105 hover-sfx">
                <h3 class="text-xl font-extrabold text-yellow-400 text-center uppercase tracking-wider">🏆 Leaderboard</h3>

                <div class="flex-1 overflow-y-auto max-h-96 mt-4 pr-1 custom-scrollbar">
                    <ul class="space-y-3">
                        @foreach ($leaderboard as $index => $entry)
                            @php
                                $isCurrentUser = $entry->name === $user->name;
                            @endphp
                            <li
                                class="flex items-center p-3 rounded-xl shadow-md transition transform hover:scale-105 hover-sfx
                    {{ $isCurrentUser ? 'bg-yellow-400 border-l-8 border-yellow-300 text-gray-900 scale-105' : 'bg-white hover:bg-gray-100' }}">
                                <span class="font-bold text-lg w-8 text-center">{{ $index + 1 }}</span>
                                <img src="{{ asset('storage/' . $entry->profile_photo) }}"
                                    class="w-10 h-10 rounded-full border-2 border-gray-500 shadow">
                                <div class="ml-3 flex-1 overflow-hidden">
                                    <p
                                        class="text-sm font-semibold truncate {{ $isCurrentUser ? 'text-gray-900' : 'text-gray-700' }}">
                                        {{ $entry->name }}
                                    </p>
                                </div>
                                <span class="text-gray-500 text-sm font-bold whitespace-nowrap">{{ $entry->weekly_score }}
                                    pts</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>


            <!-- Lives -->
            <div class="col-span-2 text-center">
                <h3
                    class="text-2xl font-extrabold tracking-wide uppercase 
        {{ $student->lives == 0 ? 'text-gray-500 animate-flash' : ($student->lives == 1 ? 'text-red-600 animate-pulse' : ($student->lives == 2 ? 'text-yellow-500 animate-bounce' : 'text-red-500')) }}">
                    {{ $student->lives == 0 ? '💀 Out of Lives!' : '🔥 Lives' }}
                </h3>

                <div class="flex justify-center mt-2 space-x-2">
                    @for ($i = 0; $i < 5; $i++)
                        <span
                            class="text-5xl transition transform 
                {{ $i < $student->lives ? 'text-red-500 drop-shadow-[0_0_10px_rgba(255,0,0,0.7)] scale-125' : 'text-gray-500 opacity-30 scale-90' }} 
                {{ $student->lives == 2 && $i == 0 ? 'animate-warning' : '' }}
                {{ $student->lives == 1 && $i == 0 ? 'animate-danger' : '' }}
                {{ $student->lives == 0 ? 'animate-shake' : '' }}">
                            ❤️
                        </span>
                    @endfor
                </div>

                <!-- Efek "Game Over" Saat Lives Habis -->
                @if ($student->lives == 0)
                    <p class="mt-4 text-gray-400 font-semibold text-lg">No more lives! Wait for refill.</p>
                @endif
            </div>
            <style>
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: scale(0.95);
                    }

                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                .animate-fadeIn {
                    animation: fadeIn 0.5s ease-out;
                }

                @keyframes wiggle {

                    0%,
                    100% {
                        transform: rotate(0deg);
                    }

                    25% {
                        transform: rotate(-5deg);
                    }

                    50% {
                        transform: rotate(5deg);
                    }

                    75% {
                        transform: rotate(-5deg);
                    }
                }

                .animate-wiggle {
                    animation: wiggle 0.3s ease-in-out infinite;
                }

                @keyframes shake {

                    0%,
                    100% {
                        transform: translateX(0);
                    }

                    25% {
                        transform: translateX(-3px);
                    }

                    50% {
                        transform: translateX(3px);
                    }

                    75% {
                        transform: translateX(-3px);
                    }
                }

                .animate-shake {
                    animation: shake 0.5s ease-in-out infinite;
                }

                @keyframes flash {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.3;
                    }
                }

                .animate-flash {
                    animation: flash 0.8s ease-in-out infinite;
                }

                @keyframes warning {

                    0%,
                    100% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.1);
                    }
                }

                .animate-warning {
                    animation: warning 0.5s ease-in-out infinite;
                }

                @keyframes danger {

                    0%,
                    100% {
                        transform: rotate(0deg);
                        opacity: 1;
                    }

                    25% {
                        transform: rotate(-5deg);
                        opacity: 0.5;
                    }

                    50% {
                        transform: rotate(5deg);
                        opacity: 1;
                    }

                    75% {
                        transform: rotate(-5deg);
                        opacity: 0.5;
                    }
                }

                .animate-danger {
                    animation: danger 0.3s ease-in-out infinite;
                }

                .custom-scrollbar::-webkit-scrollbar {
                    width: 6px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 9999px;
                }

                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
            </style>
        </div>
    </div>
    <audio id="hover-sound" src="{{ asset('sfx/hover.mp3') }}"></audio>
    <audio id="click-sound" src="{{ asset('sfx/click.mp3') }}"></audio>
    <script>
        function showModal(icon, name, description, isUnlocked, unlockedAt) {
            document.getElementById('modalIcon').src = icon;
            document.getElementById('modalName').innerText = name;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalStatus').innerText = isUnlocked ?
                `Unlocked at: ${unlockedAt}` :
                '🔒 Not unlocked yet';

            const modal = document.getElementById('achievementModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('achievementModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
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
@endsection
