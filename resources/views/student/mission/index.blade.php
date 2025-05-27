@extends('student.layouts.app')

@section('content')
    <div class="animate-fadeIn">
        <div class="max-w-6xl mx-auto px-4 py-10 relative">
            <!-- Sound Effects -->
            <audio id="hover-sound" src="{{ asset('sfx/hover.mp3') }}"></audio>
            <audio id="click-star-sound" src="{{ asset('sfx/click.mp3') }}"></audio>
            <audio id="show-detail-sound" src="{{ asset('sfx/showdetailbox.mp3') }}"></audio>
            <!-- Header -->
            <div class="flex justify-between items-start flex-wrap gap-6">
                <!-- Greeting & Lives -->
                <div>
                    <h2 id="welcome-text"
                        class="text-4xl font-extrabold text-yellow-500 drop-shadow-lg tracking-wide uppercase animate-fadeIn relative inline-block transition-transform duration-500 typing-effect">
                        <span class="text-shadow-glow">
                            Haloo <span id="username">{{ explode(' ', $student->user->name)[0] }} 👋</span>
                        </span>
                    </h2>
                    <!-- Lives -->
                    <div class="flex items-center mt-2">
                        <span class="text-red-500 text-2xl">❤️</span>
                        <p class="ml-2 text-white text-lg font-semibold" id="livesDisplay">Lives: ...</p>
                    </div>
                    <p id="lifeCountdown" class="text-sm text-gray-400 mt-1"></p>
                </div>

                <!-- Rank, XP & Streak -->
                <div class="flex flex-wrap gap-4">
                    <!-- Rank & XP Box -->
                    <div onclick="openRankModal()"
                        class="cursor-pointer bg-gradient-to-r from-blue-700 to-purple-700 text-white p-4 rounded-xl shadow-3xl flex items-center w-64 border-2 border-blue-300 transition transform hover:scale-105 hover:shadow-yellow-300 duration-300 group relative overflow-hidden">

                        <span class="text-3xl mr-4 drop-shadow-md animate-pulse">🏆</span>

                        <div>
                            <p class="text-md font-bold group-hover:text-yellow-300 transition">
                                {{ $student->current_rank?->name }}</p>

                            @php
                                $minExp = $student->current_rank?->min_exp ?? 0;
                                $maxExp = $student->current_rank?->max_exp ?? 100;
                                $currentExp = $student->exp;
                                $expInCurrentRank = $currentExp - $minExp;
                                $range = $maxExp - $minExp;
                                $progressPercent = $range > 0 ? ($expInCurrentRank / $range) * 100 : 0;
                            @endphp
                            <div class="w-40 bg-gray-300 rounded-full h-2 mt-1 relative overflow-hidden">
                                <div class="bg-yellow-400 h-2 rounded-full absolute transition-all duration-700 animate-glow"
                                    style="width: {{ $progressPercent }}%">
                                </div>
                            </div>
                            <p class="text-xs mt-1 text-gray-200">{{ $student->exp }} /
                                {{ $student->current_rank?->max_exp }} XP</p>
                        </div>
                    </div>


                    <!-- Streak -->
                    <div
                        class="p-4 rounded-lg shadow-2xl flex items-center w-32 justify-center border-2 transition-all
                    {{ $student->streak >= 1 ? 'bg-orange-500 border-yellow-300 animate-fire' : 'bg-gray-700 border-gray-500' }}">
                        <span
                            class="text-2xl mr-2 {{ $student->streak >= 1 ? 'text-yellow-300 glow-fire' : 'text-gray-400' }}">
                            🔥
                        </span>
                        <div>
                            <p
                                class="text-sm font-semibold text-center {{ $student->streak >= 1 ? 'text-white' : 'text-gray-400' }}">
                                Streak</p>
                            <p
                                class="text-lg font-bold text-center {{ $student->streak >= 1 ? 'text-yellow-200' : 'text-gray-500' }}">
                                {{ $student->streak }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Score -->
            <div
                class="p-4 rounded-lg shadow-2xl flex items-center w-56 bg-gradient-to-r from-green-600 to-emerald-500 border-2 border-green-300 transition transform hover:scale-105 hover:shadow-emerald-300 hover-sfx">
                <span class="text-3xl mr-3 drop-shadow-md text-white">🎯</span>
                <div>
                    <p class="text-md font-bold text-white">Total Score</p>
                    <p class="text-xl font-extrabold text-yellow-200">{{ number_format($student->total_score ?? 0) }} pts
                    </p>
                </div>
            </div>
            <!-- Sections -->
            @foreach ($sections as $section)
                <div class="mt-12">
                    <!-- Section Title -->
                    <div
                        class="bg-gray-800 text-white p-4 rounded-lg shadow-lg flex justify-center items-center border-2 border-gray-600">
                        <p class="text-lg font-bold uppercase text-white text-center">
                            Section {{ $section->order }} - <span
                                class="text-yellow-300 font-semibold normal-case">{{ $section->name }}</span>
                        </p>
                    </div>

                    <!-- Challenge Path -->
                    <div class="flex flex-col items-center mt-6 space-y-8">
                        @foreach ($section->challenges as $index => $challenge)
                            <div
                                class="w-full flex justify-center transform {{ $index % 2 == 0 ? '-translate-x-8' : 'translate-x-8' }}">
                                <button
                                    onclick="playClickStarSound() ; showChallenge('{{ route('student.mission.showChallenge', $challenge->id) }}')"
                                    class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-700 text-white rounded-full shadow-3xl 
                                    flex items-center justify-center hover:scale-125 transition duration-300 transform hover:rotate-12
                                    border-4 border-white hover:border-yellow-400 glow hover-sfx">
                                    ⭐
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Mission Detail Box (Gaming UI) -->
            <div id="missionBox"
                class="fixed top-20 right-10 w-96 bg-gradient-to-br from-gray-900 to-gray-800 p-6 rounded-2xl shadow-[0_0_20px_rgba(255,255,0,0.4)] 
hidden opacity-0 transition-all duration-500 transform scale-95 border border-yellow-300
hover:scale-105 flex flex-col items-center text-white animate-3dFlip backdrop-blur-md">

                <!-- Close Button -->
                <button onclick="playClickStarSound(); closeMissionBox()"
                    class="hover-sfx absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center 
hover:scale-110 transition transform hover:rotate-12 shadow-md border border-red-700">
                    ✖
                </button>

                <!-- Challenge Title -->
                <h3 class="text-2xl font-extrabold text-center tracking-wide uppercase text-yellow-300" id="missionTitle">
                    Mission</h3>

                <!-- Divider -->
                <div class="w-20 h-1 bg-yellow-400 rounded-full my-2"></div>

                <!-- Attempt Number -->
                <p class="text-md text-gray-300 mt-2">🌀 Attempt: <span id="attemptNumber"
                        class="font-bold text-yellow-200"></span></p>

                <!-- Target -->
                <p class="text-md text-gray-300 mt-2">🎯 Target: <span id="questionCount"
                        class="font-bold text-yellow-200"></span> Soal Benar</p>

                <!-- Reward XP -->
                <div
                    class="hover-sfx flex items-center mt-4 bg-yellow-500 text-gray-900 px-4 py-2 rounded-lg shadow-inner border border-yellow-300 hover:shadow-yellow-300 transition">
                    <span class="text-lg font-extrabold mr-2">🏆</span>
                    <p class="text-lg font-bold">+<span id="challengeExp"></span> XP</p>
                </div>

                <!-- Total Score -->
                <div
                    class="hover-sfx flex items-center mt-2 bg-blue-500 text-gray-900 px-4 py-2 rounded-lg shadow-inner border border-blue-300 hover:shadow-blue-300 transition">
                    <span class="text-lg font-extrabold mr-2">🔢</span>
                    <p class="text-lg font-bold"><span id="challengeScore"></span> Score</p>
                </div>

                <!-- Dynamic Button -->
                <button id="missionStartBtn" onclick="playClickStarSound(); startChallenge()"
                    class="hover-sfx bg-gradient-to-r from-yellow-500 to-orange-500 text-black px-8 py-3 mt-6 rounded-lg 
shadow-lg hover:shadow-yellow-300 transition transform hover:scale-110 border border-yellow-400 font-bold text-lg">
                    🚀 START MISSION
                </button>
                <button id="missionReviewBtn"
                    class="hidden hover-sfx bg-gradient-to-r from-green-400 to-blue-400 text-white px-8 py-3 mt-4 rounded-lg 
    shadow-lg hover:shadow-blue-300 transition transform hover:scale-110 border border-blue-300 font-bold text-lg">
                    🔁 REVIEW
                </button>
            </div>

            <!-- Modal Lives Habis -->
            <div id="livesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-red-600 p-6 rounded-lg text-center shadow-lg popUp w-96">
                    <h2 class="text-2xl font-bold text-white">⛔ Lives Habis!</h2>
                    <p class="text-white mt-2">Kamu kehabisan lives! Tunggu beberapa saat atau gunakan item untuk
                        mendapatkan
                        lives baru.</p>
                    <div class="mt-4 flex justify-center space-x-4">
                        <button onclick="closeLivesModal()" class="bg-gray-800 text-white px-4 py-2 rounded-lg">❌
                            Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rank Modal -->
        <div id="rankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-gray-900 p-6 rounded-lg shadow-xl w-full max-w-lg">
                <h2 class="text-2xl font-bold text-yellow-400 mb-4 text-center">📈 Daftar Rank & EXP</h2>
                <ul class="space-y-3 max-h-72 overflow-y-auto">
                    @foreach ($allRanks as $rank)
                        <li class="border-b border-gray-700 pb-2">
                            <p class="text-white font-semibold">{{ $rank->name }}</p>
                            <p class="text-sm text-gray-400">EXP: {{ $rank->min_exp }} - {{ $rank->max_exp }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="text-center mt-6">
                    <button onclick="closeRankModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        ✖️ Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Animations & Scripts -->
    <style>
        /* Efek Glowing */
        .text-shadow-glow {
            text-shadow: 0px 0px 10px rgba(255, 215, 0, 0.8),
                0px 0px 20px rgba(255, 165, 0, 0.6);
        }

        /* Animasi Fade-in */
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

        /* Animasi Mengetik */
        @keyframes typing {
            from {
                width: 0;
            }

            to {
                width: 35%;
            }
        }

        .typing-effect {
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid white;
            display: inline-block;
            animation: typing 2s steps(16, end);
        }

        @keyframes fireGlow {
            0% {
                text-shadow: 0px 0px 10px rgba(255, 165, 0, 0.7);
            }

            50% {
                text-shadow: 0px 0px 20px rgba(255, 140, 0, 1);
            }

            100% {
                text-shadow: 0px 0px 10px rgba(255, 165, 0, 0.7);
            }
        }

        .glow-fire {
            animation: fireGlow 1s infinite alternate;
        }

        @keyframes fireMove {
            0% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-3px) scale(1.05);
            }

            100% {
                transform: translateY(0px) scale(1);
            }
        }

        .animate-fire {
            animation: fireMove 0.8s infinite alternate ease-in-out;
        }

        @keyframes flip {
            0% {
                transform: rotateY(90deg) scale(0.8);
                opacity: 0;
            }

            100% {
                transform: rotateY(0deg) scale(1);
                opacity: 1;
            }
        }

        .animate-3dFlip {
            animation: flip 0.5s ease-in-out;
        }

        @keyframes glow {
            0% {
                box-shadow: 0px 0px 5px rgba(255, 255, 0, 0.5);
            }

            50% {
                box-shadow: 0px 0px 20px rgba(255, 255, 0, 1);
            }

            100% {
                box-shadow: 0px 0px 5px rgba(255, 255, 0, 0.5);
            }
        }

        .animate-glow {
            animation: glow 1.5s infinite alternate;
        }

        .shadow-3xl {
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.4);
        }

        .glow {
            transition: all 0.3s ease-in-out;
        }

        .glow:hover {
            box-shadow: 0px 0px 20px rgba(255, 255, 0, 0.7);
        }

        @keyframes pop {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pop {
            animation: pop 0.5s ease-in-out;
        }
    </style>

    <script>
        function openRankModal() {
            document.getElementById('rankModal').classList.remove('hidden');
        }

        function closeRankModal() {
            document.getElementById('rankModal').classList.add('hidden');
        }

        function playShowDetailSound() {
            const audio = document.getElementById("show-detail-sound");
            if (audio) {
                audio.currentTime = 0;
                audio.play();
            }
        }

        function playClickStarSound() {
            const audio = document.getElementById("click-star-sound");
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
                el.removeEventListener("mouseenter", playHoverSound); // bersihkan duplikat
                el.addEventListener("mouseenter", playHoverSound);
            });
        }

        function showChallenge(url) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('missionTitle').innerText = data.title;
                    document.getElementById('questionCount').innerText = data.question_count;
                    document.getElementById('challengeExp').innerText = data.exp;
                    document.getElementById('challengeScore').innerText = data.score;
                    if (data.attempt_number) {
                        document.getElementById('attemptNumber').innerText = data.attempt_number;
                    } else {
                        document.getElementById('attemptNumber').innerText =
                            "0";
                    }
                    document.getElementById('missionBox').setAttribute("data-challenge-id", data.id);

                    const btn = document.getElementById('missionStartBtn');
                    const reviewBtn = document.getElementById('missionReviewBtn');
                    if (data.is_perfect) {
                        btn.classList.add('hidden');
                        reviewBtn.classList.remove('hidden');
                        reviewBtn.onclick = function() {
                            window.location.href = `/student/review/${data.id}/${data.attempt_number}`;
                        };
                    } else {
                        btn.classList.remove('hidden');
                        reviewBtn.classList.add('hidden');
                    }

                    let box = document.getElementById('missionBox');
                    box.classList.remove('hidden');
                    setTimeout(() => {
                        box.classList.remove('opacity-0', 'scale-95');
                        box.classList.add('opacity-100', 'scale-100');
                        playShowDetailSound();
                        rebindHoverSound();
                    }, 50);

                });
        }

        function closeMissionBox() {
            let box = document.getElementById('missionBox');
            box.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                box.classList.add('hidden');
            }, 300);
        }

        function startChallenge() {
            let challengeId = document.getElementById("missionBox").getAttribute("data-challenge-id");

            $.ajax({
                url: "{{ route('student.check.lives') }}",
                type: "GET",
                success: function(response) {
                    if (response.lives > 0) {
                        $.ajax({
                            url: "{{ route('student.start.challenge') }}",
                            type: "POST",
                            data: {
                                challenge_id: challengeId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function() {
                                window.location.href = `/student/question/${challengeId}`;
                            },
                            error: function(xhr) {
                                console.error(xhr.responseJSON);
                                alert("Gagal menyimpan challenge.");
                            }
                        });
                    } else {
                        $("#livesModal").removeClass("hidden");
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert("Terjadi kesalahan saat memeriksa lives.");
                }
            });
        }

        function closeLivesModal() {
            $("#livesModal").addClass("hidden");
        }

        function updateLivesDisplay() {
            $.ajax({
                url: "{{ route('student.check.lives') }}",
                type: "GET",
                success: function(response) {
                    $('#livesDisplay').text("Lives: " + response.lives);

                    if (response.lives < 5 && response.next_life_at) {
                        const livesToRecover = 5 - response.lives;
                        const nextLifeTime = new Date(response.next_life_at).getTime();
                        const fullRecoveryTime = nextLifeTime + (livesToRecover - 1) * 60 * 60 * 1000;

                        function updateCountdown() {
                            const now = new Date().getTime();
                            const remaining = fullRecoveryTime - now;

                            if (remaining <= 0) {
                                updateLivesDisplay(); // refresh when done
                                return;
                            }

                            const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

                            $('#lifeCountdown').text(`⏳ Full lives in ${hours}h ${minutes}m ${seconds}s`);

                            setTimeout(updateCountdown, 1000);
                        }

                        updateCountdown();
                    } else {
                        $('#lifeCountdown').text('');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    $('#livesDisplay').text("Lives: ?");
                    $('#lifeCountdown').text('');
                }
            });
        }

        $(document).ready(function() {
            updateLivesDisplay();
        });
    </script>
@endsection
