<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeTableCategory;
use Illuminate\Http\Request;

class TimeTableCategoryController extends Controller
{
    // Display all categories
    public function index()
    {
        $records = TimeTableCategory::latest()->get();
        return view('admin.time_table_categories.index', compact('records'));
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        TimeTableCategory::create($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    // Edit category (AJAX)
    public function edit($id)
    {
        $record = TimeTableCategory::findOrFail($id);
        return response()->json($record);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        $record = TimeTableCategory::findOrFail($id);
        $record->update($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    // Delete category
    public function destroy($id)
    {
        $record = TimeTableCategory::findOrFail($id);
        $record->delete();

        return response()->json(['success' => true]);
    }
}
