<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::all();
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create()
    {
        return view('admin.semesters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        Semester::create($request->all());
        return redirect()->route('semesters.index')->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        return response()->json($semester);
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $semester->update($request->all());
        return response()->json(['success' => true]);

    }

    public function destroy(Semester $semester)
    {
        $semester->delete();
        return response()->json(['success' => true]);
    }
}
