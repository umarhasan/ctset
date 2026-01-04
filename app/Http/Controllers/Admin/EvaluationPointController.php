<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationPoint;
use Illuminate\Http\Request;

class EvaluationPointController extends Controller
{
    public function index()
    {
        $records = EvaluationPoint::latest()->get();
        return view('admin.evaluation_points.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        EvaluationPoint::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(EvaluationPoint::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        EvaluationPoint::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        EvaluationPoint::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
