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
        $request->validate(['title'=>'required']);
        VideoMainCategory::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(VideoMainCategory::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        VideoMainCategory::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        VideoMainCategory::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
