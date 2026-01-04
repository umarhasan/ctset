<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TraineeEvaluation;
use Illuminate\Http\Request;

class TraineeEvaluationController extends Controller
{
    public function index()
    {
        $records = TraineeEvaluation::latest()->get();
        return view('admin.trainee_evaluations.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        TraineeEvaluation::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(TraineeEvaluation::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        TraineeEvaluation::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        TraineeEvaluation::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
