<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rank Up!</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: white;
            font-family: 'Poppins', sans-serif;
        }

        .glow-box {
            border: 3px solid #00ffff;
            box-shadow: 0px 0px 15px #00ffff;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 10px #00ffff;
            }

            50% {
                box-shadow: 0 0 25px #00ffff;
            }

            100% {
                box-shadow: 0 0 10px #00ffff;
            }
        }

        .pop-in {
            animation: popIn 0.8s ease-out;
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .loader {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #00ffff;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        setTimeout(function() {
            window.location.href =
                "{{ route('student.challenge.summary', ['challenge_id' => $challenge_id, 'attempt_number' => $attempt_number]) }}";
        }, 6000);
    </script>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

    <audio id="rankup-audio" autoplay>
        <source src="{{ asset('audio/rankup.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <div class="bg-gray-900 p-10 rounded-2xl glow-box pop-in max-w-md w-full text-center">
        <h1 class="text-4xl font-extrabold text-green-400 mb-2 animate-bounce">🔓 Rank Unlocked!</h1>
        <p class="text-sm text-gray-400 mb-1">You’ve proven your skills. Your new rank is:</p>

        <div class="text-3xl font-extrabold text-yellow-300 my-5 tracking-wider uppercase">
            {{ $student->ranks->sortByDesc('min_exp')->first()?->name }}
        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-400 mb-2">Redirecting to challenge result...</p>
            <div class="loader"></div>
        </div>
    </div>

</body>

</html>
