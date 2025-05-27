<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Review Jawaban</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-900 to-black min-h-screen text-white font-sans py-10 px-6">

    <div class="max-w-5xl mx-auto">
        <h1 class="text-4xl font-extrabold text-yellow-400 mb-10 text-center drop-shadow">🔁 Review Jawaban</h1>

        @foreach ($answers as $questionId => $answerGroup)
            @php
                $question = $answerGroup->first()->question;
                $isCorrect = $answerGroup->first()->is_correct;
                $correctAnswers = $question->answers->where('is_correct', true);
            @endphp

            <div
                class="mb-10 p-6 rounded-2xl shadow-lg border-2 transition-all
                border-gray-700 hover:bg-gray-900 hover:border-green-400">

                <!-- Judul Soal -->
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-white">Soal {{ $loop->iteration }}</h2>
                    <p class="mt-2 text-gray-200 leading-relaxed">{{ $question->description }}</p>

                    @if ($question->question_image)
                        <img src="{{ asset('storage/' . $question->question_image) }}" alt="Gambar Soal"
                            class="mt-4 w-full max-h-64 object-contain rounded border border-gray-600 shadow-md">
                    @endif

                    <p class="text-lg font-semibold text-yellow-300 mt-4">❓ {{ $question->question_text }}</p>
                </div>

                <!-- Jawaban Kamu -->
                <div class="mt-6">
                    <h3 class="text-sm font-bold text-white mb-2">📌 Jawaban Kamu:</h3>
                    <ul class="pl-5 list-disc space-y-2">
                        @foreach ($answerGroup as $answer)
                            <li class="text-md {{ $answer->is_correct ? 'text-green-300' : 'text-red-300' }}">
                                <span
                                    class="font-medium">{{ $answer->selectedAnswer?->answer ?? $answer->selected_answer }}</span>
                                @if ($answer->selectedAnswer?->answer_image)
                                    <img src="{{ asset('storage/' . $answer->selectedAnswer->answer_image) }}"
                                        alt="Answer Image" class="mt-1 max-w-xs border rounded shadow-sm">
                                @endif
                                <span class="ml-2 text-sm">
                                    {{ $answer->is_correct ? '✅ Benar' : '❌ Salah' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Jawaban Benar -->
                <div class="mt-6">
                    <h3 class="text-sm font-bold text-white mb-2">✅ Jawaban yang Benar:</h3>
                    <ul class="pl-5 list-disc space-y-2">
                        @foreach ($correctAnswers as $correct)
                            <li class="text-green-300">
                                <span class="font-medium">{{ $correct->answer }}</span>
                                @if ($correct->answer_image)
                                    <img src="{{ asset('storage/' . $correct->answer_image) }}"
                                        alt="Correct Answer Image" class="mt-1 max-w-xs border rounded shadow-sm">
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

        <!-- Tombol Kembali -->
        <div class="text-center mt-16">
            <a href="{{ route('student.mission.index') }}"
                class="bg-yellow-400 hover:bg-yellow-300 text-black px-8 py-4 rounded-xl font-bold shadow-lg text-lg transition">
                🏠 Kembali ke Misi
            </a>
        </div>
    </div>

</body>

</html>
