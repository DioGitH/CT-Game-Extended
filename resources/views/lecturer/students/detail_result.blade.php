@extends('lecturer.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-8 border-b pb-2 tracking-tight">
            Answer Details - {{ $student->user->name }}
            <span class="text-sm font-medium text-gray-500">Challenge ID: {{ $challenge->id }} | Attempt:
                {{ $attempt }}</span>
        </h1>

        <!-- Attempt Overview (Minimized) -->
        <div class="flex justify-end mb-6">
            <div class="w-full md:w-1/3 bg-white border border-gray-200 shadow-sm rounded-lg p-4">
                <h2 class="text-base font-semibold text-blue-600 mb-3">Attempt Overview</h2>
                <ul class="space-y-1 text-sm text-gray-700 leading-relaxed">
                    <li><strong>Attempt:</strong> {{ $result->attempt_number }}</li>
                    <li><strong>Score:</strong> {{ $result->total_score }}</li>
                    <li><strong>EXP:</strong> {{ $result->total_exp }}</li>
                </ul>
            </div>
        </div>

        <!-- Student Answers Highlight -->
        <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 shadow-xl rounded-xl p-6">
            <h2 class="text-2xl font-bold text-blue-700 mb-6">📘 Student Answers</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left font-medium text-gray-800 divide-y divide-blue-200">
                    <thead class="bg-blue-600 text-white uppercase text-xs font-semibold tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Question</th>
                            <th class="px-6 py-3">Answer</th>
                            <th class="px-6 py-3">Detail</th>
                            <th class="px-6 py-3">Result</th>
                            <th class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($answers as $questionId => $groupedAnswers)
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 max-w-xl whitespace-normal leading-snug">
                                    {{ Str::limit($groupedAnswers->first()->question->question_text ?? 'Question not found', 120) }}
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ $groupedAnswers->pluck('selected_answer')->join(', ') }}
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ $groupedAnswers->pluck('selectedAnswer.answer')->filter()->join(', ') }}
                                </td>
                                <td class="px-6 py-4 font-bold">
                                    @php
                                        $isAllCorrect = $groupedAnswers->every(fn($ans) => $ans->is_correct);
                                        $isAnyCorrect = $groupedAnswers->contains(fn($ans) => $ans->is_correct);
                                    @endphp

                                    @if ($isAllCorrect)
                                        <span class="text-green-600">✔ All Correct</span>
                                    @elseif ($isAnyCorrect)
                                        <span class="text-yellow-600">✔ Partially Correct</span>
                                    @else
                                        <span class="text-red-500">✘ Incorrect</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($groupedAnswers->first()->question)
                                        <a href="{{ route('lecturer.questions.show', $groupedAnswers->first()->question->id) }}"
                                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-1.5 px-4 rounded-full shadow transition duration-200">
                                            View Question
                                        </a>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-6 italic">
                                    No answers available for this attempt.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-10 text-right">
            <a href="{{ route('lecturer.students.show', $student->id) }}"
                class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-semibold text-sm py-2 px-6 rounded shadow transition duration-200">
                ← Back to Student Overview
            </a>
        </div>
    </div>
@endsection
