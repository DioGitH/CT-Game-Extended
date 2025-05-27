@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Question</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="question-form" action="{{ route('lecturer.questions.update', $question->id) }}" method="POST"
        enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- Challenge Selection -->
        <div class="mb-4">
            <label for="challenge_id" class="block text-gray-700 font-bold mb-2">Challenge</label>
            <select name="challenge_id" id="challenge_id"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
                @foreach ($challenges as $challenge)
                    <option value="{{ $challenge->id }}" {{ $challenge->id == $question->challenge_id ? 'selected' : '' }}>
                        {{ $challenge->title }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>{{ old('description', $question->description) }}</textarea>
        </div>

        <!-- Question Type -->
        <div class="mb-4">
            <label for="type" class="block text-gray-700 font-bold mb-2">Question Type</label>
            <select name="type_display" id="type_display"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2 bg-gray-200 cursor-not-allowed"
                disabled>
                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice
                </option>
                <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>True or False</option>
                <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay</option>
            </select>
            <!-- Hidden input agar tipe soal tetap dikirim -->
            <input type="hidden" name="type" value="{{ $question->type }}">
        </div>

        <!-- Question Text -->
        <div class="mb-4">
            <label for="question_text" class="block text-gray-700 font-bold mb-2">Question Text</label>
            <textarea name="question_text" id="question_text" rows="4"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        <!-- Question Image -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Current Image</label>
            <div id="question-image-container">
                @if ($question->question_image)
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $question->question_image) }}" alt="Question Image"
                            class="w-32 h-32 object-cover rounded mb-4" id="current-image">

                        <!-- Tombol hapus gambar -->
                        <button type="button" onclick="deleteQuestionImage()"
                            class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 rounded-full text-sm">X</button>
                    </div>

                    <!-- Hidden input untuk menandai gambar akan dihapus -->
                    <input type="hidden" name="delete_question_image" id="delete-question-image" value="0">
                @else
                    <p class="text-gray-400">No image uploaded.</p>
                @endif
            </div>

            <!-- Upload Image - Tetap Ada Walau Gambar Lama Masih Ada -->
            <div id="upload-new-image-container">
                <label for="question_image" class="block text-gray-700 font-bold mb-2">Upload New Image</label>
                <input type="file" name="question_image" id="question_image"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2">
            </div>
        </div>

        <!-- Score, EXP, and Time Limit -->
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="score" class="block text-gray-700 font-bold mb-2">Score</label>
                <input type="number" name="score" id="score" value="{{ old('score', $question->score) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
            </div>

            <div>
                <label for="exp" class="block text-gray-700 font-bold mb-2">EXP</label>
                <input type="number" name="exp" id="exp" value="{{ old('exp', $question->exp) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
            </div>
        </div>

        <!-- Answers Section -->
        <div class="mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">✅ Answers</h3>

            <div id="answers-section">
                @if ($question->type === 'multiple_choice')
                    <!-- Multiple Choice Answers -->
                    <div id="multiple-choice-section">
                        @foreach ($question->answers as $index => $answer)
                            <div class="answer-group flex items-center mb-4 space-x-2">
                                <!-- Answer Text -->
                                <input type="text" name="answers[]" value="{{ $answer->answer }}"
                                    class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2"
                                    placeholder="Answer Text" required>

                                <!-- Answer Image -->
                                <div id="answer-image-container-{{ $index }}" class="relative">
                                    @if ($answer->answer_image)
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="Answer Image"
                                                class="w-20 h-20 object-cover rounded shadow">
                                        </div>
                                    @endif
                                </div>

                                <!-- Upload Answer Image -->
                                <div>
                                    <input type="file" name="answer_images[]" id="answer_image_{{ $index }}"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2">
                                </div>

                                <!-- Hidden Input for is_correct -->
                                <input type="hidden" name="is_correct[]" value="{{ $answer->is_correct ? '1' : '0' }}"
                                    class="is-correct-hidden">

                                <!-- Correct Answer Selection (Checkbox) -->
                                <label class="inline-flex items-center correct-option">
                                    <input type="checkbox" name="is_correct[{{ $index }}]" value="1"
                                        class="mr-2 correct-checkbox" {{ $answer->is_correct ? 'checked' : '' }}
                                        onclick="toggleCorrectAnswer(this)">
                                    Correct
                                </label>

                                <!-- Remove Answer Button -->
                                <button type="button"
                                    class="bg-red-500 text-white px-2 py-1 rounded-md shadow hover:bg-red-400 transition"
                                    onclick="removeAnswer(this)">Remove</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-answer-btn"
                        class="bg-green-500 text-white px-4 py-2 rounded-md shadow hover:bg-green-400 transition"
                        onclick="addAnswer()">Add Answer</button>
                @elseif ($question->type === 'true_false')
                    <!-- True or False Selection -->
                    <div id="true-false-section">
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg shadow-sm">
                            <label class="inline-flex items-center">
                                <input type="radio" name="correct_answer" value="true" class="mr-2"
                                    {{ $question->answers->where('answer', 'True')->first()->is_correct ? 'checked' : '' }}>
                                <span class="text-gray-800 font-medium">True</span>
                            </label>
                        </div>

                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg shadow-sm">
                            <label class="inline-flex items-center">
                                <input type="radio" name="correct_answer" value="false" class="mr-2"
                                    {{ $question->answers->where('answer', 'False')->first()->is_correct ? 'checked' : '' }}>
                                <span class="text-gray-800 font-medium">False</span>
                            </label>
                        </div>
                    </div>
                @elseif ($question->type === 'essay')
                    <!-- Essay Answer -->
                    <div id="essay-section">
                        <label class="block text-gray-700 font-bold mb-2">Correct Answer</label>
                        <textarea name="answers[]" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>{{ $question->answers->first()->answer ?? '' }}</textarea>
                    </div>
                @endif
            </div>
        </div>

        <!-- Buttons Section -->
        <div class="mt-6 flex justify-between">
            <!-- Cancel Button -->
            <a href="{{ route('lecturer.questions.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-400 transition duration-300 ease-in-out">
                Cancel
            </a>

            <!-- Update Question Button -->
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition duration-300 ease-in-out">
                Update Question
            </button>
        </div>
    </form>

    <script>
        function addAnswer() {
            const answerSection = document.getElementById('answers-section');
            const index = document.querySelectorAll('.answer-group').length;

            const answerField = `
        <div class="answer-group flex items-center mb-4 space-x-2" data-index="${index}">
            <input type="text" name="answers[]" placeholder="Answer Text" 
                class="w-1/2 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>

            <input type="file" name="answer_images[]" class="w-1/2 p-2">

            <!-- Hidden Input for is_correct -->
            <input type="hidden" name="is_correct[]" value="0" class="is-correct-hidden">

            <label class="inline-flex items-center correct-option">
                <input type="checkbox" name="is_correct[${index}]" value="1" class="mr-2 correct-checkbox"
                    onclick="toggleCorrectAnswer(this)">
                Correct
            </label>

            <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-md shadow hover:bg-red-400 transition"
                onclick="removeAnswer(this)">Remove</button>
        </div>`;

            answerSection.insertAdjacentHTML('beforeend', answerField);
        }

        function toggleCorrectAnswer(checkbox) {
            let hiddenInput = checkbox.closest('.answer-group').querySelector('.is-correct-hidden');
            hiddenInput.value = checkbox.checked ? '1' : '0';
        }

        function removeAnswer(button) {
            const answerGroup = button.closest('.answer-group');
            answerGroup.remove();
        }

        function updateCorrectAnswerIndexes() {
            const answerGroups = document.querySelectorAll('.answer-group');
            answerGroups.forEach((group, index) => {
                group.setAttribute('data-index', index);
                const radioInput = group.querySelector('input[name="correct_answer"]');
                if (radioInput) {
                    radioInput.value = index;
                }
            });
        }

        function deleteAnswerImage(index) {
            document.getElementById('answer-image-container-' + index).innerHTML =
                "<p class='text-gray-400'>No image uploaded.</p>";

            // Set nilai hidden input agar gambar dihapus saat form disubmit
            let deleteInput = document.getElementById('delete-answer-image-' + index);
            if (!deleteInput) {
                let inputHidden = document.createElement("input");
                inputHidden.type = "hidden";
                inputHidden.name = "delete_answer_images[" + index + "]";
                inputHidden.id = "delete-answer-image-" + index;
                inputHidden.value = "1";
                document.getElementById('question-form').appendChild(inputHidden);
            } else {
                deleteInput.value = "1";
            }

            // Tampilkan input upload gambar baru setelah dihapus
            document.getElementById('upload-answer-image-container-' + index).classList.remove("hidden");
        }

        function deleteQuestionImage() {
            document.getElementById('question-image-container').innerHTML =
                "<p class='text-gray-400'>No image uploaded.</p>";

            // Set nilai hidden input agar gambar dihapus
            let deleteInput = document.getElementById('delete-question-image');
            if (!deleteInput) {
                let inputHidden = document.createElement("input");
                inputHidden.type = "hidden";
                inputHidden.name = "delete_question_image";
                inputHidden.id = "delete-question-image";
                inputHidden.value = "1";
                document.getElementById('question-form').appendChild(inputHidden);
            } else {
                deleteInput.value = "1";
            }

            // Tampilkan input upload gambar baru setelah dihapus
            document.getElementById('upload-new-image-container').classList.remove("hidden");
        }
    </script>
@endsection
