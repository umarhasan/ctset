<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoMainCategory;
use Illuminate\Http\Request;

class VideoMainCategoryController extends Controller
{
    public function index()
    {
        $records = VideoMainCategory::latest()->get();
        return view('admin.video_main_categories.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        VideoMainCategory::create($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $record = VideoMainCategory::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        $record = VideoMainCategory::findOrFail($id);
        $record->update($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        VideoMainCategory::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
