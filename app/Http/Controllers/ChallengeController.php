<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Models\Section;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::orderBy('name')->get();

        $sectionSearch = $request->input('section_id'); 

        $challenges = Challenge::when($sectionSearch, function ($query, $sectionSearch) {
            return $query->where('section_id', $sectionSearch);
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('lecturer.challenges.index', compact('challenges', 'sections', 'sectionSearch'));
    }

    public function create(Request $request)
    {
        $sections = Section::orderBy('order')->get();

        $selectedSectionId = $request->input('section_id');

        return view('lecturer.challenges.create', compact('sections', 'selectedSectionId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:255',
        ]);

        Challenge::create([
            'section_id' => $request->section_id,
            'title' => $request->title,
            'total_exp' => $request->total_exp,
            'total_score' => $request->total_score,
        ]);

        return redirect()->route('lecturer.challenges.index')->with('status', 'success');
    }


    public function show(Challenge $challenge)
    {
        return view('lecturer.challenges.show', compact('challenge'));
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        $sections = Section::orderBy('order', 'asc')->get(); // Ambil semua section untuk dropdown
        return view('lecturer.challenges.edit', compact('challenge', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:255',
            'total_exp' => 'integer|min:0',
            'total_score' => 'integer|min:0',
        ]);

        $challenge = Challenge::findOrFail($id);
        $challenge->update([
            'section_id' => $request->section_id,
            'title' => $request->title,
            'total_exp' => $request->total_exp,
            'total_score' => $request->total_score,
        ]);

        return redirect()->route('lecturer.challenges.index')->with('success', 'Challenge updated successfully!');
    }


    public function destroy($id)
    {
        try {
            $challenge = Challenge::findOrFail($id);
            $title = $challenge->title; // Simpan judul challenge sebelum dihapus
            $challenge->delete();

            // Kirim status dan judul challenge via session flash
            return redirect()->route('lecturer.challenges.index')
                ->with(['status' => 'delete-success', 'deleted_title' => $title]);
        } catch (\Exception $e) {
            return redirect()->route('lecturer.challenges.index')
                ->with('status', 'delete-error');
        }
    }
}
