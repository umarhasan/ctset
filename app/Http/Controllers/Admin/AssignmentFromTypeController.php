<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentFromType;
use Illuminate\Http\Request;

class AssignmentFromTypeController extends Controller
{
    public function index()
    {
        $records = AssignmentFromType::latest()->get();
        return view('admin.assignment_from_types.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        AssignmentFromType::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(AssignmentFromType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        AssignmentFromType::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        AssignmentFromType::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
