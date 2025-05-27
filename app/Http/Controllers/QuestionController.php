<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Challenge;
use App\Models\Answer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Display a listing of the questions
    public function index(Request $request)
    {
        $challenges = Challenge::all();
        $selectedChallenge = $request->query('challenge_id');

        if ($selectedChallenge) {
            $questions = Question::where('challenge_id', $selectedChallenge)
                ->with('challenge')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $questions = Question::with('challenge')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('lecturer.questions.index', compact('questions', 'challenges', 'selectedChallenge'));
    }

    public function show($id)
    {
        $question = Question::with('answers', 'challenge')->findOrFail($id);
        return view('lecturer.questions.show', compact('question'));
    }

    // Show the form for creating a new question
    public function create()
    {
        $challenges = Challenge::all();
        return view('lecturer.questions.create', compact('challenges'));
    }

    // Store a newly created question in storage
    public function store(Request $request)
    {
        $request->validate([
            'challenge_id'    => 'required|exists:challenges,id',
            'type'            => 'required|in:multiple_choice,true_false,essay',
            'description'     => 'nullable|string',
            'question_text'   => 'required|string',
            'score'           => 'required|integer',
            'exp'             => 'required|integer',
            'question_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'answers'         => 'required_if:type,multiple_choice,essay|array',
            'is_correct'      => 'nullable|array',
            'answer_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'correct_answer'  => 'required_if:type,true_false|in:true,false',
        ]);

        // Simpan data pertanyaan
        $data = $request->only(['challenge_id', 'type', 'description', 'question_text', 'score', 'exp']);

        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }

        $question = Question::create($data);
        $question->challenge->recalculateTotals();

        // Handling berdasarkan tipe soal
        if ($request->type === 'true_false') {
            // Simpan jawaban True dan False dengan is_correct sesuai pilihan
            Answer::create([
                'question_id' => $question->id,
                'answer'      => 'True',
                'is_correct'  => $request->correct_answer === "true" ? 1 : 0,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'answer'      => 'False',
                'is_correct'  => $request->correct_answer === "false" ? 1 : 0,
            ]);
        } elseif ($request->type === 'multiple_choice') {
            foreach ($request->answers as $index => $answer) {
                $answerData = [
                    'question_id' => $question->id,
                    'answer'      => $answer,
                    'is_correct'  => isset($request->is_correct[$index]) && $request->is_correct[$index] == "1" ? 1 : 0,
                ];

                if ($request->hasFile("answer_images.$index")) {
                    $answerData['answer_image'] = $request->file("answer_images.$index")->store('answers', 'public');
                }

                Answer::create($answerData);
            }
        } elseif ($request->type === 'essay') {
            Answer::create([
                'question_id' => $question->id,
                'answer'      => $request->answers[0],
                'is_correct'  => 1,
            ]);
        }

        return redirect()->route('lecturer.questions.index')->with('success', 'Question created successfully!');
    }

    // Show the form for editing the specified question
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $challenges = Challenge::all();
        return view('lecturer.questions.edit', compact('question', 'challenges'));
    }

    // Update the specified question in storage
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'challenge_id'    => 'required|exists:challenges,id',
            'type'            => 'required|in:multiple_choice,true_false,essay',
            'description'     => 'nullable|string',
            'question_text'   => 'required|string',
            'score'           => 'required|integer',
            'exp'             => 'required|integer',
            'question_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'answers'         => 'required_if:type,multiple_choice,essay|array',
            'is_correct'      => 'nullable|array',
            'answer_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correct_answer'  => 'required_if:type,true_false|in:true,false', // Untuk True/False
        ]);

        // Update data pertanyaan
        $data = $request->only(['challenge_id', 'type', 'description', 'question_text', 'score', 'exp']);

        // **🔴 Hapus question image jika user memilih delete**
        if ($request->has('delete_question_image') && $request->delete_question_image == "1") {
            if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
                Storage::disk('public')->delete($question->question_image);
            }
            $data['question_image'] = null;
        }

        // **🔴 Jika ada gambar baru, hapus yang lama & simpan yang baru**
        if ($request->hasFile('question_image')) {
            if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
                Storage::disk('public')->delete($question->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }

        $question->update($data);

        // **🔴 Hapus jawaban lama & gambar terkait**
        foreach ($question->answers as $answer) {
            // **🔴 Hapus gambar jawaban jika user memilih delete**
            if (!empty($request->delete_answer_images[$answer->id]) && $request->delete_answer_images[$answer->id] == "1") {
                if (!empty($answer->answer_image) && Storage::disk('public')->exists($answer->answer_image)) {
                    Storage::disk('public')->delete($answer->answer_image);
                }
                $answer->update(['answer_image' => null]);
            }
        }

        $question->answers()->delete();

        $question->update($data);

        // Hapus jawaban lama sebelum update
        $question->answers()->delete();

        // Handling berdasarkan tipe soal
        if ($request->type === 'true_false') {
            // Simpan jawaban True dan False
            Answer::create([
                'question_id' => $question->id,
                'answer'      => 'True',
                'is_correct'  => $request->correct_answer === "true" ? 1 : 0,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'answer'      => 'False',
                'is_correct'  => $request->correct_answer === "false" ? 1 : 0,
            ]);
        } elseif ($request->type === 'multiple_choice') {
            foreach ($request->answers as $index => $answer) {
                $answerData = [
                    'question_id' => $question->id,
                    'answer'      => $answer,
                    'is_correct'  => isset($request->is_correct[$index]) && $request->is_correct[$index] == "1" ? 1 : 0,
                ];

                if ($request->hasFile("answer_images.$index")) {
                    $answerData['answer_image'] = $request->file("answer_images.$index")->store('answers', 'public');
                }

                Answer::create($answerData);
            }
        } elseif ($request->type === 'essay') {
            // Essay hanya memiliki satu jawaban tanpa opsi benar/salah
            Answer::create([
                'question_id' => $question->id,
                'answer'      => $request->answers[0],
                'is_correct'  => 1,
            ]);
        }
        $question->challenge->recalculateTotals();

        return redirect()->route('lecturer.questions.index')->with('success', 'Question updated successfully!');
    }

    // Remove the specified question from storage
    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
            Storage::disk('public')->delete($question->question_image);
        }

        foreach ($question->answers as $answer) {
            if ($answer->answer_image && Storage::disk('public')->exists($answer->answer_image)) {
                Storage::disk('public')->delete($answer->answer_image);
            }
        }

        $question->answers()->delete();
        $question->delete();

        $question->challenge->recalculateTotals();

        return redirect()->route('lecturer.questions.index')->with('error', 'Question deleted successfully.');
    }
}
