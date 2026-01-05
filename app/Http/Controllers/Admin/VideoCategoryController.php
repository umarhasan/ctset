<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoCategoryController extends Controller
{
    public function index()
    {
        $records = VideoCategory::latest()->get();
        return view('admin.video_categories.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        VideoCategory::create($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $record = VideoCategory::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title'    => 'required|string|max:255',
        ]);

        $record = VideoCategory::findOrFail($id);
        $record->update($request->only('category', 'title'));

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        VideoCategory::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
