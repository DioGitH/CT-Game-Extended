@php
    use Carbon\Carbon;
    $start = Carbon::parse($challengeResult->created_at);
    $end = Carbon::parse($challengeResult->ended_at ?? now());
    $duration = $start->diff($end);
    $pesan =
        $challengeResult->correct_answers > $challengeResult->wrong_answers
            ? $motivasiBenar[array_rand($motivasiBenar)]
            : $motivasiSalah[array_rand($motivasiSalah)];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">
    <title>Challenge Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: white;
            font-family: 'Poppins', sans-serif;
        }

        .neon-border {
            border: 3px solid #00ffff;
            box-shadow: 0px 0px 15px #00ffff;
        }

        .fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }

        .popUp {
            animation: popUp 0.5s ease-in-out;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 flex items-center justify-center p-4">

    <div class="w-full max-w-3xl bg-gray-900 shadow-2xl rounded-xl p-8 neon-border fadeIn text-center">
        <h1 class="text-2xl font-extrabold text-yellow-400 uppercase mb-4">Challenge Completed! 🎉</h1>

        <!-- Durasi -->
        <div class="bg-gray-800 p-3 rounded-lg mb-3">
            <p class="text-sm text-gray-300">⏱ Duration</p>
            <p class="text-lg font-bold text-pink-400">{{ $duration->format('%i min %s sec') }}</p>
        </div>

        <!-- Skor & EXP -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-800 p-3 rounded-lg">
                <p class="text-sm text-gray-300">🏆 Score</p>
                <p class="text-xl font-extrabold text-green-400">{{ $challengeResult->total_score }}</p>
            </div>
            <div class="bg-gray-800 p-3 rounded-lg">
                <p class="text-sm text-gray-300">⚡ EXP</p>
                <p class="text-xl font-extrabold text-blue-400">{{ $challengeResult->total_exp }}</p>
            </div>
        </div>

        <!-- Benar / Salah -->
        <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="bg-green-500 p-3 rounded-lg">
                <p class="text-sm font-semibold">✅ Correct</p>
                <p class="text-xl font-extrabold">{{ $challengeResult->correct_answers }}</p>
            </div>
            <div class="bg-red-500 p-3 rounded-lg">
                <p class="text-sm font-semibold">🚫 Wrong</p>
                <p class="text-xl font-extrabold">{{ $challengeResult->wrong_answers }}</p>
            </div>
        </div>

        <!-- Pesan -->
        <p class="mt-4 text-sm text-gray-300 font-semibold">
            {{ $pesan }}
        </p>

        <!-- Tombol -->
        <div class="mt-4 flex justify-center space-x-3">
            <a href="{{ route('student.mission.index') }}"
                class="bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm hover:bg-yellow-300 transition">
                🏠 Missions
            </a>

            @if ($isPerfect)
                <a href="{{ route('student.review', ['challenge' => $challengeResult->challenge_id, 'attempt' => $challengeResult->attempt_number]) }}"
                    class="bg-purple-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-400 transition">
                    🔁 Review
                </a>
            @else
                <a href="{{ route('student.start.question', ['challenge_id' => $challengeResult->challenge_id]) }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-400 transition">
                    🔄 Retry
                </a>
            @endif
        </div>
    </div>

</body>

</html>
