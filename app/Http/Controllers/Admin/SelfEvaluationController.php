<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SelfEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = SelfEvaluation::orderBy('created_at','desc')->paginate(10);
        return view('admin.self-evaluations.index', compact('evaluations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'goals'=>'nullable|string',
            'goal_plan_actions'=>'nullable|array',
            'goal_plan_actions.*.goal'=>'nullable|string',
            'goal_plan_actions.*.plan'=>'nullable|string',
            'question_actions'=>'nullable|array',
            'question_actions.*.title'=>'nullable|string',
            'question_actions.*.question'=>'nullable|string',
            'status'=>'required|in:active,inactive',
        ]);

        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        $data = $request->all();
        $data['goal_plan_actions'] = json_encode($request->goal_plan_actions ?? []);
        $data['question_actions'] = json_encode($request->question_actions ?? []);

        $evaluation = SelfEvaluation::create($data);
        return response()->json(['success'=>true,'message'=>'Saved successfully!','evaluation'=>$evaluation]);
    }

    public function edit($id)
    {
        $evaluation = SelfEvaluation::findOrFail($id);
        return response()->json($evaluation);
    }

    public function update(Request $request,$id)
    {
        $evaluation = SelfEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'goals'=>'nullable|string',
            'goal_plan_actions'=>'nullable|array',
            'goal_plan_actions.*.goal'=>'nullable|string',
            'goal_plan_actions.*.plan'=>'nullable|string',
            'question_actions'=>'nullable|array',
            'question_actions.*.title'=>'nullable|string',
            'question_actions.*.question'=>'nullable|string',
            'status'=>'required|in:active,inactive',
        ]);

        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        $data = $request->all();
        $data['goal_plan_actions'] = json_encode($request->goal_plan_actions ?? []);
        $data['question_actions'] = json_encode($request->question_actions ?? []);

        $evaluation->update($data);

        return response()->json(['success'=>true,'message'=>'Updated successfully!','evaluation'=>$evaluation]);
    }

    public function destroy($id)
    {
        $evaluation = SelfEvaluation::findOrFail($id);
        $evaluation->delete();

        return response()->json(['success'=>true,'message'=>'Deleted successfully!']);
    }
}
