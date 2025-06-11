<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge - Question</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-y: auto;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-y: auto;
            padding-top: 20px;
        }

        .content {
            flex-grow: 1;
            padding-bottom: 20px;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8, #d1d9e6);
            /* Light gradient background */
            color: #333;
            /* Dark text color for better contrast */
            font-family: 'Poppins', sans-serif;
        }

        .glow {
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.8);
        }

        .neon-border {
            border: 2px solid #00ffff;
            box-shadow: 0px 0px 10px #00ffff;
        }

        .progress-bar {
            background: linear-gradient(to right, #00f260, #0575e6);
            box-shadow: 0px 0px 15px rgba(0, 242, 96, 0.7);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fadeIn {
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes popUp {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .popUp {
            animation: popUp 0.5s ease-in-out;
        }

        .selected-answer {
            background-color: #2563eb;
            color: white;
            border: 2px solid #1e3a8a;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.7);
        }
    </style>
</head>

<body class="container flex items-center justify-center h-screen">
    <audio id="sfx-show-question" src="{{ asset('sfx/showquestion.mp3') }}"></audio>
    <audio id="sfx-hover" src="{{ asset('sfx/hover.mp3') }}"></audio>
    <audio id="sfx-click" src="{{ asset('sfx/click.mp3') }}"></audio>
    <audio id="sfx-correct" src="{{ asset('sfx/correct.mp3') }}"></audio>
    <audio id="sfx-wrong" src="{{ asset('sfx/wrong.mp3') }}"></audio>
    <div class="content max-w-3xl w-full bg-white shadow-xl rounded-lg p-6 relative neon-border fadeIn overflow-y-auto">
            <!-- Lives Counter -->
            <div class="flex justify-center items-center mt-4">
                <span class="text-red-500 text-2xl font-bold mr-2">❤️</span>
                <p id="lives-count" class="text-xl font-bold text-red-400">Lives: ...</p>
            </div>

            <!-- Tombol X (Exit) -->
            <button onclick="modalExit()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl">
                ✖
            </button>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-300 rounded-full h-3 mt-6 overflow-hidden">
                <div id="progress-bar" class="progress-bar h-3 rounded-full" style="width: {{ $progress }}%;"></div>
            </div>

            <!-- Question Description -->
            <p class="mt-4 text-gray-800 font-medium text-center text-lg">
                {!! nl2br(e($question->description)) !!}
            </p>

            <!-- Question Image -->
            @if ($question->question_image)
                <div class="flex justify-center my-4">
                    <img src="{{ asset('storage/' . $question->question_image) }}" class="max-h-48 rounded-lg shadow-lg">
                </div>
            @endif

            <!-- Question Text -->
            <p class="text-lg font-bold text-yellow-500 mt-6">Tantangan:</p>
            <p class="text-xl font-extrabold text-center text-yellow-600 mt-2">{!! nl2br(e($question->question_text)) !!}</p>

            <!-- Soal Multiple Choice -->
            @if ($question->type == 'multiple_choice')
                @php
                    $correctCount = $question->answers->where('is_correct', 1)->count();
                @endphp

                <div class="grid grid-cols-2 gap-4 mt-6">
                    @foreach ($question->answers as $answer)
                        @if ($correctCount > 1)
                            <!-- Checkbox-style (multiple answers allowed) -->
                            <button onclick="toggleMultiAnswer(this, {{ $answer->id }})"
                                class="answer-btn-multi bg-gray-200 text-gray-800 p-4 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg">
                                @if ($answer->answer_image)
                                    <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="answer image"
                                        class="max-h-20 w-auto mx-auto mb-2">
                                @endif
                                {{ $answer->answer }}
                            </button>
                        @else
                            <!-- Radio-style (only one answer) -->
                            <button onclick="checkAnswer({{ $answer->id }}, {{ $answer->is_correct }})"
                                class="answer-btn bg-gray-200 text-gray-800 p-4 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg">
                                @if ($answer->answer_image)
                                    <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="answer image"
                                        class="max-h-20 w-auto mx-auto mb-2">
                                @endif
                                {{ $answer->answer }}
                            </button>
                        @endif
                    @endforeach
                </div>

                @if ($correctCount > 1)
                    <button onclick="submitMultiAnswer()" id="check-btn-multi"
                        class="mt-4 hidden bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-400 transition">✅
                        Submit Answer</button>
                @endif
            @endif

            <!-- **Jenis Soal: True/False** -->
            @if ($question->type == 'true_false')
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <button onclick="checkAnswer('true', {{ $question->correct_answer == 'true' ? 1 : 0 }})"
                        class="answer-btn bg-green-600 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-800 transition transform hover:scale-105 shadow-lg">
                        ✅ True
                    </button>
                    <button onclick="checkAnswer('false', {{ $question->correct_answer == 'false' ? 1 : 0 }})"
                        class="answer-btn bg-red-600 text-white p-4 rounded-lg text-lg font-semibold hover:bg-red-800 transition transform hover:scale-105 shadow-lg">
                        ❌ False
                    </button>
                </div>
            @endif

            <!-- **Jenis Soal: Essay** -->
            @if ($question->type == 'essay')
                <div class="mt-6">
                    <textarea id="essay-answer" class="w-full border border-gray-500 bg-gray-200 text-gray-800 p-3 rounded-lg"
                        placeholder="Type your answer here..."></textarea>
                    <button onclick="checkEssayAnswer({{ $question->id }})"
                        class="bg-blue-500 text-white px-6 py-3 mt-3 rounded-lg shadow-md hover:bg-blue-400 transition transform hover:scale-105">
                        ✅ Submit Answer
                    </button>
                </div>
            @endif

            <!-- Result Message -->
            <div id="result-message" class="mt-4 hidden text-white p-3 rounded-lg text-lg text-center popUp"></div>

            <!-- Tombol Next Question -->
            <button id="next-btn" onclick="nextQuestion()"
                class="hidden bg-purple-500 text-white px-6 py-3 mt-3 rounded-lg shadow-md hover:bg-purple-400 transition transform hover:scale-110">
                ➡️ Continue
            </button>
        </div>
        <!-- Realtime Focus Detection Section -->
        <div class="absolute top-6 right-12 flex flex-col items-center bg-white rounded-lg overflow-hidden neon-border">
            {{-- <video id="video" autoplay muted playsinline width="320" height="240" class="rounded-md shadow-md border border-gray-300"></video> --}}
            <div id="my_camera" class="rounded-lg shadow-md"></div>
            <p id="focus-status" class="mt-2 text-md font-bold text-gray-700">Status Fokus: <span class="text-yellow-500">Mendeteksi...</span></p>
        </div>

    <!-- **Modal Konfirmasi Keluar** -->
    <div id="exit-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-yellow-500 p-6 rounded-lg text-center shadow-lg popUp">
            <p class="text-xl font-semibold text-black">Apakah Anda yakin ingin meninggalkan progress?</p>
            <div class="mt-4 flex justify-center space-x-4">
                <button onclick="hideExitModal()" class="bg-gray-800 text-white px-4 py-2 rounded-lg">❌ Cancel</button>
                <button onclick="confirmExit()" class="bg-red-600 text-white px-4 py-2 rounded-lg">✅ Yes</button>
            </div>
        </div>
    </div>

    <!-- Modal Nyawa Habis -->
    <div id="out-of-lives-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-red-600 p-6 rounded-lg text-center shadow-lg popUp">
            <p class="text-2xl font-extrabold text-white">💔 Nyawa Habis!</p>
            <p class="text-white mt-2">Kamu kehabisan nyawa. Silakan coba lagi nanti.</p>
            <div class="mt-4">
                <a href="{{ route('student.mission.index') }}"
                    class="bg-white text-red-600 px-5 py-2 rounded-lg font-bold hover:bg-gray-200 transition">Kembali ke
                    Misi</a>
            </div>
        </div>
    </div>

    <!-- Modal Jawaban Kosong -->
    <div id="empty-answer-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-yellow-400 text-black p-6 rounded-lg text-center shadow-lg popUp w-80">
            <h2 class="text-xl font-bold mb-2">⚠️ Jawaban Kosong</h2>
            <p class="text-sm">Masukkan jawaban terlebih dahulu sebelum menekan submit.</p>
            <div class="mt-4">
                <button onclick="closeEmptyAnswerModal()"
                    class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    ❌ Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- **Modal Log Fokus** -->
    <div id="log-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-yellow-500 p-6 rounded-lg text-center shadow-lg popUp">
            <p class="text-xl font-semibold text-black"></p>
            <div class="mt-4 flex justify-center space-x-4">
                <button onclick="$('#log-modal').addClass('hidden')" class="bg-gray-800 text-white px-4 py-2 rounded-lg">OK</button>
            </div>
        </div>
    </div>

    <!-- **Script Handling** -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <script>
        const socket = io("{{ env('WS_CONNECTION') }}"); // ganti ke URL backend Flask kamu
        const username = @json(auth()->user()->name);

        // Setup webcam
        Webcam.set({
            width: 240,
            height: 180,
            image_format: 'jpeg',
            jpeg_quality: 60
        });
        Webcam.attach('#my_camera');

        // Tunggu sampai webcam siap
        Webcam.on('live', function () {
            console.log("✅ Webcam sudah aktif");

            // Kirim username ke backend
            socket.emit('register_username', { username });

            // Kirim frame setiap 0.5 detik
            setInterval(() => {
                Webcam.snap(function (data_uri) {
                    socket.emit('frame_camera', {
                        frame: data_uri
                    });
                });
            }, 300);
        });

        Webcam.on('error', function (err) {
            console.error("🚫 Gagal mengakses kamera:", err);
        });

        // Menerima status fokus
        socket.on('receive_status', (data) => {
            const statusElem = document.getElementById("focus-status");
            if (data.focused) {
                statusElem.innerHTML = 'Status Fokus: <span class="text-green-600">Fokus ✅</span>';
            } else {
                statusElem.innerHTML = 'Status Fokus: <span class="text-red-600">Tidak Fokus ❌</span>';
            }
        });

        // Peringatan tidak fokus
        socket.on("not_focused_warning", (data) => {
            // Set pesan ke modal log-modal
            $("#log-modal p.text-xl").text(data.message);
            $("#log-modal").removeClass("hidden");
        });
    </script>
    <script>
        function playSound(id) {
            const audio = document.getElementById(id);
            if (audio) {
                audio.currentTime = 0;
                audio.play();
            }
        }
        let selectedMultiAnswers = [];

        function toggleMultiAnswer(button, answerId) {
            $(button).toggleClass('bg-blue-500');
            const index = selectedMultiAnswers.indexOf(answerId);

            if (index > -1) {
                selectedMultiAnswers.splice(index, 1);
            } else {
                selectedMultiAnswers.push(answerId);
            }

            if (selectedMultiAnswers.length > 0) {
                $('#check-btn-multi').removeClass('hidden');
            } else {
                $('#check-btn-multi').addClass('hidden');
            }
        }

        function submitMultiAnswer() {
            $.ajax({
                url: "{{ route('student.question.checkMultiple') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    question_id: {{ $question->id }},
                    selected_answers: selectedMultiAnswers
                },
                success: function(data) {
                    let resultMessage = $("#result-message");
                    let nextButton = $("#next-btn");

                    if (data.correct) {
                        let praises = ["🔥 Epic!", "✅ Mantap!", "👏 GG!", "🎉 Luar Biasa!", "💯 Jagoan!"];
                        resultMessage.text(praises[Math.floor(Math.random() * praises.length)]);
                        resultMessage.addClass("bg-green-500");
                        playSound("sfx-correct");
                    } else {
                        let encouragements = ["😢 Jangan menyerah!", "💪 Coba lagi!", "🤔 Pikirkan baik-baik!",
                            "👀 Perhatikan lagi!", "🙌 Semangat!"
                        ];
                        resultMessage.text(encouragements[Math.floor(Math.random() * encouragements.length)]);
                        resultMessage.addClass("bg-red-500");
                        playSound("sfx-wrong");
                        updateLives();
                    }

                    resultMessage.removeClass("hidden");
                    $(".answer-btn-multi").attr("disabled", true);
                    nextButton.removeClass("hidden");
                    $('#check-btn-multi').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    $('#check-btn-multi').text("Submitted ✅");

                    if (data.lives !== undefined && data.lives <= 0) {
                        socket.emit('stop_camera', {})
                        $("#out-of-lives-modal").removeClass("hidden");
                        

                        // Redirect otomatis setelah beberapa detik
                        setTimeout(() => {
                            window.location.href = "{{ route('student.mission.index') }}";
                        }, 2500); // 2.5 detik
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert("Error: " + xhr.responseJSON.message);
                }
            });
        }

        function updateLives() {
            $.ajax({
                url: "{{ route('student.check.lives') }}",
                type: "GET",
                success: function(response) {
                    $("#lives-count").text("Lives: " + response.lives);
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                }
            });
        }

        $(document).ready(function() {
            updateLives();
            playSound("sfx-show-question");
            $("button, .answer-btn, .answer-btn-multi, #check-btn-multi, #next-btn").on("mouseenter", function() {
                playSound("sfx-hover");
            });
            $("button, textarea").on("click", function() {
                playSound("sfx-click");
            });
        });

        let selectedAnswer = null;

        function selectAnswer(button, answerId) {
            selectedAnswer = answerId;

            $(".answer-btn").removeClass("bg-blue-500").addClass("bg-gray-800");

            $(button).removeClass("bg-gray-800").addClass("bg-blue-500");

            $("#check-btn").removeClass("hidden");
        }

        function checkAnswer(answerId, isCorrect) {
            $(".answer-btn").removeClass("selected-answer");
            $(`[onclick*="${answerId}"]`).addClass("selected-answer");
            $.ajax({
                url: "{{ route('student.question.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    question_id: {{ $question->id }},
                    selected_answer: answerId,
                    is_correct: isCorrect ? 1 : 0
                },
                success: function(data) {
                    let resultMessage = $("#result-message");
                    let nextButton = $("#next-btn");

                    if (data.is_correct) {
                        let praises = ["🔥 Epic!", "✅ Mantap!", "👏 GG!", "🎉 Luar Biasa!", "💯 Jagoan!"];
                        resultMessage.text(praises[Math.floor(Math.random() * praises.length)]);
                        resultMessage.addClass("bg-green-500");
                        playSound("sfx-correct");
                    } else {
                        let encouragements = ["😢 Jangan menyerah!", "💪 Coba lagi!", "🤔 Pikirkan baik-baik!",
                            "👀 Perhatikan lagi!", "🙌 Semangat!"
                        ];
                        resultMessage.text(encouragements[Math.floor(Math.random() * encouragements.length)]);
                        resultMessage.addClass("bg-red-500");
                        playSound("sfx-wrong");
                        updateLives();
                    }

                    resultMessage.removeClass("hidden");
                    $(".answer-btn").attr("disabled", true);
                    nextButton.removeClass("hidden");

                    if (data.lives !== undefined && data.lives <= 0) {
                        socket.emit('stop_camera', {})
                        $("#out-of-lives-modal").removeClass("hidden");
                        setTimeout(() => {
                            window.location.href = "{{ route('student.mission.index') }}";
                        }, 2500); // 2.5 detik
                    }

                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert("Terjadi kesalahan: " + xhr.responseJSON.message);
                }
            });
        }

        function checkEssayAnswer(questionId) {
            let answer = $("#essay-answer").val().trim();

            if (answer === "") {
                $("#empty-answer-modal").removeClass("hidden");
                return;
            }

            $.ajax({
                url: "{{ route('student.check.essay') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    question_id: questionId,
                    answer: answer
                },
                success: function(data) {
                    let resultMessage = $("#result-message");
                    let nextButton = $("#next-btn");

                    if (data.correct) {
                        let praises = ["🔥 Epic!", "✅ Mantap!", "👏 GG!", "🎉 Luar Biasa!", "💯 Jagoan!"];
                        resultMessage.text(praises[Math.floor(Math.random() * praises.length)]);
                        resultMessage.addClass("bg-green-500");
                        playSound("sfx-correct");
                    } else {
                        let encouragements = ["😢 Jangan menyerah!", "💪 Coba lagi!", "🤔 Pikirkan baik-baik!",
                            "👀 Perhatikan lagi!", "🙌 Semangat!"
                        ];
                        resultMessage.text(encouragements[Math.floor(Math.random() * encouragements.length)]);
                        resultMessage.addClass("bg-red-500");
                        playSound("sfx-wrong");
                    }

                    resultMessage.removeClass("hidden");
                    nextButton.removeClass("hidden");
                    $('button[onclick^="checkEssayAnswer"]').prop('disabled', true).addClass(
                        'opacity-50 cursor-not-allowed');
                    $('button[onclick^="checkEssayAnswer"]').text("Submitted ✅");
                    if (data.lives !== undefined && data.lives <= 0) {
                        socket.emit('stop_camera', {})
                        $("#out-of-lives-modal").removeClass("hidden");
                        

                        // Redirect otomatis setelah beberapa detik
                        setTimeout(() => {
                            window.location.href = "{{ route('student.mission.index') }}";
                        }, 2500); // 2.5 detik
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert("Terjadi kesalahan: " + xhr.responseJSON.message);
                }
            });
        }

        function closeEmptyAnswerModal() {
            $("#empty-answer-modal").addClass("hidden");
        }

        function nextQuestion() {
            window.location.href = "{{ route('student.next.question', ['challenge_id' => $question->challenge_id]) }}";
        }

        function modalExit() {
            $("#exit-modal").removeClass("hidden");
        }

        function confirmExit() {
            let challengeId = "{{ $question->challenge_id }}";
            socket.emit('stop_camera', {})

            $.ajax({
                url: "{{ route('student.question.exit') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    challenge_id: challengeId
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('student.mission.index') }}";
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert("Terjadi kesalahan: " + xhr.responseJSON.message);
                }
            });
        }

        function hideExitModal() {
            $("#exit-modal").addClass("hidden");
        }
    </script>
</body>

</html>
