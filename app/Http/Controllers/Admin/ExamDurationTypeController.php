<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamDurationType;
use Illuminate\Http\Request;

class ExamDurationTypeController extends Controller
{
    public function index()
    {
        $records = ExamDurationType::latest()->get();
        return view('admin.exam_duration_types.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        ExamDurationType::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(ExamDurationType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        ExamDurationType::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        ExamDurationType::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
