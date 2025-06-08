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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        <!-- Log Fokus -->
        <div class=" bg-sky-950 p-1.5 rounded-lg mt-3 rounded-b-none">
            <p class="text-sm text-gray-300">Log Tidak Fokus</p>
        </div>
        <div class="grid grid-cols-2 gap-x-1 gap-y-0">
            <div class="bg-sky-900 p-1.5 rounded-lg rounded-t-none">
                <p class="text-sm">Jumlah</p>
                <p id="unfocused-count" class="text-xs font-semibold"></p>
            </div>
            <div class="bg-sky-900 p-1.5 rounded-lg rounded-t-none">
                <p class="text-sm">Total Durasi</p>
                <p id="total-duration" class="text-xs font-semibold">0 min 0 sec</p>
            </div>
        </div>
        <div class=" bg-transparent p-1.5 rounded-lg mt-1 w-1/2 mx-auto">
            <p class="text-sm text-gray-300 bg-sky-950 p-1.5 rounded-lg">History Log</p>
            <p id="unfocused-timestamps" class="text-xs mx-auto max-h-28 overflow-y-auto rounded-lg
                [&::-webkit-scrollbar]:w-1
                [&::-webkit-scrollbar-track]:rounded-full
                [&::-webkit-scrollbar-track]:bg-gray-100
                [&::-webkit-scrollbar-thumb]:rounded-full
                [&::-webkit-scrollbar-thumb]:bg-gray-300
                dark:[&::-webkit-scrollbar-track]:bg-neutral-700
                dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"></p>
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
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        const socket = io("{{ env('WS_CONNECTION') }}");
        const username = @json(auth()->user()->name);

        socket.emit('register_username', { username });
        socket.emit('stop_camera', {})
        const challengeresult_id = "{{ $challengeResult->id }}";

        socket.on('session_summary', (data) => {
            fetch("{{ route('student.challange.logFocus') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    challengeresult_id: challengeresult_id,
                    logs: data.unfocused_timestamps,
                })
            })


            const unfocused_count = data.unfocused_count;
            const total_unfocused_duration = data.total_unfocused_duration;
            const unfocused_timestamps = data.unfocused_timestamps;

            // Display the unfocused timestamps in the blade with improved formatting
            const timestampsContainer = document.getElementById('unfocused-timestamps');
            timestampsContainer.innerHTML = unfocused_timestamps.map(entry => {
                const start = new Date(entry.start).toLocaleTimeString('en-US', { hour12: false });
                const end = new Date(entry.end).toLocaleTimeString('en-US', { hour12: false });
                const duration = entry.duration.toFixed(2); // Limit to 2 decimal places
                return `
                    <div class=" bg-sky-900 p-1 rounded-lg my-0.5 shadow-md">
                        <p class="text-gray-300 mt-1 text-center"><span class="font-semibold text-yellow-400">Duration:</span> ${duration} sec</p>
                        <div class="flex justify-between text-gray-300 bg-gray-900 px-1.5 py-0.5 rounded-lg">
                            <p><span class="font-semibold text-yellow-400">Start:</span> ${start}</p>
                            <p><span class="font-semibold text-yellow-400">End:</span> ${end}</p>
                        </div>
                    </div>
                `;
            }).join('');
            document.getElementById('unfocused-count').innerText = unfocused_count;

            // Calculate and display the total unfocused duration
            const minutes = Math.floor(total_unfocused_duration / 60);
            const seconds_raw = total_unfocused_duration % 60;
            const seconds = seconds_raw.toFixed(2);
            document.getElementById('total-duration').innerText = `${minutes} min ${seconds} sec`;
        });
    </script>

</body>

</html>
