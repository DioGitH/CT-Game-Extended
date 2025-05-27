@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-4xl font-bold mb-6 text-gray-800 text-center">📋 Question Details</h1>

    <div class="bg-white shadow-2xl rounded-xl p-8 max-w-4xl mx-auto border border-gray-200">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-semibold text-indigo-600">Challenge: {{ $question->challenge->title }}</h2>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <strong class="text-gray-700">Type:</strong>
                <span
                    class="inline-block bg-indigo-100 text-indigo-800 text-sm font-semibold px-4 py-2 rounded-full shadow-sm">
                    {{ $question->type === 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' }}
                </span>
            </div>
        </div>

        <div class="mb-6">
            <strong class="text-gray-700 text-lg">ⓘ Description:</strong>
            <p class="mt-3 p-4 bg-gray-50 border-l-4 border-indigo-500 rounded-lg shadow-sm text-gray-800 leading-relaxed">
                {{ $question->description }}
            </p>
        </div>
        <div class="mb-6">
            <strong class="text-gray-700 text-lg">❓ Question:</strong>
            <p class="mt-3 p-4 bg-gray-50 border-l-4 border-indigo-500 rounded-lg shadow-sm text-gray-800 leading-relaxed">
                {{ $question->question_text }}
            </p>
        </div>

        <div class="mb-6 text-center">
            <strong class="text-gray-700 text-lg">🖼️ Image:</strong>
            @if ($question->question_image)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $question->question_image) }}" alt="Question Image"
                        class="w-72 h-72 object-cover rounded-lg mx-auto shadow-md border border-gray-300">
                </div>
            @else
                <p class="text-gray-400 mt-4 italic">No image available.</p>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <strong class="text-gray-700">🎯 Score:</strong>
                <span class="block bg-green-100 text-green-800 text-sm font-semibold px-4 py-2 rounded-lg mt-2 shadow">
                    {{ $question->score }} Points
                </span>
            </div>

            <div>
                <strong class="text-gray-700">⭐ EXP:</strong>
                <span class="block bg-purple-100 text-purple-800 text-sm font-semibold px-4 py-2 rounded-lg mt-2 shadow">
                    {{ $question->exp }} XP
                </span>
            </div>
        </div>

        @if ($question->type === 'multiple_choice')
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-700 mb-4">✅ Answers:</h3>
                <ul class="space-y-4">
                    @foreach ($question->answers as $answer)
                        <li class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg shadow-sm">
                            @if ($answer->answer)
                                <p class="text-gray-800 font-medium">{{ $answer->answer }}</p>
                            @endif

                            @if ($answer->answer_image)
                                <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="Answer Image"
                                    class="w-20 h-20 object-cover rounded-md shadow-md border border-gray-300">
                            @endif

                            @if ($answer->is_correct)
                                <span
                                    class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-md">
                                    Correct Answer
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif ($question->type === 'essay')
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-700 mb-4">📝 Correct Answer:</h3>
                <p class="p-4 bg-gray-50 border-l-4 border-blue-400 rounded-lg shadow-md text-gray-800 leading-relaxed">
                    {{ $question->answers->first()->answer ?? 'No answer provided.' }}
                </p>
            </div>
        @elseif ($question->type === 'true_false')
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-700 mb-4">🔢 True or False:</h3>
                <ul class="space-y-4">
                    @foreach ($question->answers as $answer)
                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg shadow-sm">
                            <span class="text-gray-800 font-medium">{{ $answer->answer }}</span>
                            @if ($answer->is_correct)
                                <span
                                    class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-md">
                                    Correct Answer
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-md">
                                    Incorrect
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('lecturer.questions.index') }}"
                class="bg-indigo-500 text-white px-6 py-3 rounded-full shadow-lg hover:bg-indigo-400 transition duration-300 ease-in-out">
                ⬅️ Back to Questions List
            </a>
        </div>
    </div>
@endsection
