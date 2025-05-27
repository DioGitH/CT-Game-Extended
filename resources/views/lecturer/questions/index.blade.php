@extends('lecturer.layouts.app')

@section('content')
    <div id="alert-container" class="mb-6">
        @if (session('success'))
            <div id="alert-box" class="bg-green-500 text-white p-4 rounded-lg shadow-md text-center">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div id="alert-box" class="bg-red-500 text-white p-4 rounded-lg shadow-md text-center">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Questions List</h1>

        <div class="flex items-center space-x-4">
            <form method="GET" action="{{ route('lecturer.questions.index') }}">
                <select name="challenge_id" onchange="this.form.submit()" class="border border-gray-300 rounded-md p-2">
                    <option value="">-- All Challenges --</option>
                    @foreach ($challenges as $challenge)
                        <option value="{{ $challenge->id }}" {{ $selectedChallenge == $challenge->id ? 'selected' : '' }}>
                            {{ $challenge->title }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('lecturer.questions.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition duration-300 ease-in-out">
                Add New Question
            </a>
        </div>
    </div>
    <input type="text" id="searchInput" placeholder="Search by description or question..."
        class="w-full sm:w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-300 focus:outline-none"
        onkeyup="filterQuestions()">
    <div class="mt-6 overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Challenge Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Desc</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Question Text</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">EXP</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($questions as $question)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $question->challenge->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 question-desc">
                            {{ Str::limit($question->description, 25) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @php
                                $typeMap = [
                                    'multiple_choice' => 'PilGan',
                                    'true_false' => 'T o F',
                                    'essay' => 'Isian',
                                ];
                            @endphp
                            {{ $typeMap[$question->type] ?? ucfirst($question->type) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 question-text">
                            {{ Str::limit($question->question_text, 15) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if ($question->question_image)
                                <img src="{{ asset('storage/' . $question->question_image) }}" alt="Question Image"
                                    class="w-20 h-20 object-cover rounded shadow-sm">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $question->score }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $question->exp }}</td>

                        <!-- Actions with Flexbox -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{ route('lecturer.questions.show', $question->id) }}"
                                    class="bg-green-500 text-white px-3 py-1 rounded-md shadow hover:bg-green-400 transition duration-300">
                                    Info
                                </a>
                                <a href="{{ route('lecturer.questions.edit', $question->id) }}"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded-md shadow hover:bg-yellow-300 transition duration-300">
                                    Edit
                                </a>
                                <form action="{{ route('lecturer.questions.destroy', $question->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded-md shadow hover:bg-red-400 transition duration-300">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr id="noResultsRow" style="display: none;">
                    <td colspan="8" class="text-center text-gray-800 py-6 italic">No matching questions found.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">
        {{ $questions->appends(['challenge_id' => request('challenge_id')])->links('pagination::tailwind') }}
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertBox = document.getElementById("alert-box");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = "opacity 0.5s";
                    alertBox.style.opacity = "0";
                    setTimeout(() => {
                        alertBox.style.display = "none";
                    }, 500);
                }, 1500);
            }
        });

        function filterQuestions() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            let anyVisible = false;

            rows.forEach(row => {
                const desc = row.querySelector(".question-desc")?.textContent.toLowerCase() || '';
                const text = row.querySelector(".question-text")?.textContent.toLowerCase() || '';

                const isMatch = desc.includes(filter) || text.includes(filter);
                row.style.display = isMatch ? "" : "none";

                if (isMatch) anyVisible = true;
            });

            const emptyMsg = document.getElementById("noResultsRow");
            if (emptyMsg) {
                emptyMsg.style.display = anyVisible ? "none" : "";
            }
        }
    </script>
@endsection
