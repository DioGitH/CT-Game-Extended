<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('order', 'asc')->paginate(10);
        return view('lecturer.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('lecturer.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order' => 'required|integer|unique:sections,order',
            'name' => 'required|string|max:255|unique:sections,name',
        ]);

        Section::create([
            'order' => $request->order,
            'name' => $request->name,
        ]);

        return redirect()->route('lecturer.sections.index')->with('success', 'Section created successfully!');
    }

    public function edit(Section $section)
    {
        return view('lecturer.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'order' => 'required|integer|unique:sections,order,' . $section->id,
            'name' => 'required|string|max:255|unique:sections,name,' . $section->id,
        ]);

        $section->update([
            'order' => $request->order,
            'name' => $request->name,
        ]);

        return redirect()->route('lecturer.sections.index')->with('success', 'Section updated successfully!');
    }

    public function destroy(Section $section)
    {
        try {
            $deletedName = $section->name;
            $section->delete();
            return redirect()->route('lecturer.sections.index')->with([
                'status' => 'delete-success',
                'deleted_name' => $deletedName,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('lecturer.sections.index')->with('status', 'delete-error');
        }
    }
    public function reorder(Request $request)
    {
        $orderedIds = $request->orderedIds;

        foreach ($orderedIds as $index => $id) {
            Section::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Sections reordered successfully!']);
    }
}
