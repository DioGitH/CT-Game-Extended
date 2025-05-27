@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">📝 Create New Question</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto border border-gray-200">
        <form id="question-form" action="{{ route('lecturer.questions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Challenge Selection -->
            <div class="mb-4">
                <label for="challenge_id" class="block text-gray-700 font-bold mb-2">Challenge</label>
                <select name="challenge_id" id="challenge_id" class="w-full border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Challenge --</option>
                    @foreach ($challenges as $challenge)
                        <option value="{{ $challenge->id }}">{{ $challenge->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Question Type -->
            <div class="mb-4">
                <label for="type" class="block text-gray-700 font-bold mb-2">Question Type</label>
                <select name="type" id="type" class="w-full border-gray-300 rounded-md p-2" required>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True or False</option>
                    <option value="essay">Essay</option>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md p-2" required></textarea>
            </div>

            <!-- Question Image -->
            <div class="mb-4">
                <label for="question_image" class="block text-gray-700 font-bold mb-2">Question Image (Optional)</label>
                <input type="file" name="question_image" id="question_image"
                    class="w-full border-gray-300 rounded-md p-2">
            </div>

            <!-- Question Text -->
            <div class="mb-4">
                <label for="question_text" class="block text-gray-700 font-bold mb-2">Question Text</label>
                <textarea name="question_text" id="question_text" rows="4" class="w-full border-gray-300 rounded-md p-2" required></textarea>
            </div>

            <!-- Score & EXP -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="score" class="block text-gray-700 font-bold mb-2">Score</label>
                    <input type="number" name="score" id="score" class="w-full border-gray-300 rounded-md p-2"
                        required>
                </div>

                <div>
                    <label for="exp" class="block text-gray-700 font-bold mb-2">EXP</label>
                    <input type="number" name="exp" id="exp" class="w-full border-gray-300 rounded-md p-2"
                        required>
                </div>
            </div>

            <!-- Answer Section -->
            <div id="answers-section" class="mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">✅ Answers</h3>

                <table class="w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-2">Answer Text</th>
                            <th class="p-2">Image (Optional)</th>
                            <th class="p-2 text-center">Correct Answer</th>
                            <th class="p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="answers-body">
                        <!-- Default answer row based on question type -->
                    </tbody>
                </table>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-6">
                <button type="button" id="add-answer-btn" onclick="addAnswer()"
                    class="bg-green-500 text-white px-4 py-2 rounded-md shadow hover:bg-green-400 transition">Add
                    Answer</button>

                <div class="space-x-4">
                    <a href="{{ route('lecturer.questions.index') }}"
                        class="bg-gray-400 text-white px-4 py-2 rounded-md shadow hover:bg-gray-500 transition">
                        Cancel
                    </a>
                    <button type="submit" id="submit-btn"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition">
                        Create Question
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const questionType = document.getElementById('type');
        const answersBody = document.getElementById('answers-body');
        const addAnswerBtn = document.getElementById('add-answer-btn');

        // Saat tipe soal berubah
        questionType.addEventListener('change', function() {
            if (this.value === 'essay') {
                resetToEssay();
            } else if (this.value === 'true_false') {
                resetToTrueFalse();
            } else {
                resetToMultipleChoice();
            }
        });

        function resetToEssay() {
            answersBody.innerHTML = `
                <tr>
                    <td class="p-2">
                        <input type="text" name="answers[]" class="w-full border-gray-300 p-2 rounded-md" required>
                    </td>
                    <td class="p-2">-</td>
                    <td class="p-2 text-center">-</td>
                    <td class="p-2 text-center">-</td>
                </tr>`;
            addAnswerBtn.style.display = 'none';
        }

        function resetToTrueFalse() {
            answersBody.innerHTML = `
                <tr>
                    <td class="p-2">True</td>
                    <td class="p-2">-</td>
                    <td class="p-2 text-center">
                        <input type="radio" name="correct_answer" value="true" checked>
                    </td>
                    <td class="p-2 text-center">-</td>
                </tr>
                <tr>
                    <td class="p-2">False</td>
                    <td class="p-2">-</td>
                    <td class="p-2 text-center">
                        <input type="radio" name="correct_answer" value="false">
                    </td>
                    <td class="p-2 text-center">-</td>
                </tr>`;
            addAnswerBtn.style.display = 'none';
        }

        function resetToMultipleChoice() {
            answersBody.innerHTML = `
                <tr class="answer-row">
                    <td class="p-2">
                        <input type="text" name="answers[]" class="w-full border-gray-300 p-2 rounded-md" required>
                    </td>
                    <td class="p-2">
                        <input type="file" name="answer_images[]" class="w-full p-2">
                    </td>
                    <td class="p-2 text-center">
                        <input type="hidden" name="is_correct[]" value="0" class="is-correct-hidden">
                        <input type="checkbox" class="correct-checkbox" onclick="toggleCorrectAnswer(this)">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-md shadow" onclick="removeAnswer(this)">Remove</button>
                    </td>
                </tr>`;
            addAnswerBtn.style.display = 'inline-block';
        }

        function addAnswer() {
            const newRow = `
                <tr class="answer-row">
                    <td class="p-2">
                        <input type="text" name="answers[]" class="w-full border-gray-300 p-2 rounded-md" required>
                    </td>
                    <td class="p-2">
                        <input type="file" name="answer_images[]" class="w-full p-2">
                    </td>
                    <td class="p-2 text-center">
                        <input type="hidden" name="is_correct[]" value="0" class="is-correct-hidden">
                        <input type="checkbox" class="correct-checkbox" onclick="toggleCorrectAnswer(this)">
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-md shadow" onclick="removeAnswer(this)">Remove</button>
                    </td>
                </tr>`;
            answersBody.insertAdjacentHTML('beforeend', newRow);
        }

        function removeAnswer(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function toggleCorrectAnswer(checkbox) {
            let hiddenInput = checkbox.closest('tr').querySelector('.is-correct-hidden');
            hiddenInput.value = checkbox.checked ? '1' : '0';
        }
    </script>
@endsection
