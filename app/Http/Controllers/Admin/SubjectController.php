<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Subject::create($request->all());
        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return response()->json($subject);

    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $subject->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['success' => true]);
    }
}
