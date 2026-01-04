<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeTableCategory;
use Illuminate\Http\Request;

class TimeTableCategoryController extends Controller
{
    public function index()
    {
        $records = TimeTableCategory::latest()->get();
        return view('admin.time_table_categories.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        TimeTableCategory::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(TimeTableCategory::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        TimeTableCategory::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        TimeTableCategory::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
