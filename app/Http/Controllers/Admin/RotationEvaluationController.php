<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RotationEvaluation;
use App\Models\RotationEvaluationSection;
use App\Models\RotationEvaluationSubitem;
use App\Models\RotationEvaluationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RotationEvaluationController extends Controller
{
    // Display list
    public function index()
    {
        $evaluations = RotationEvaluation::with('sections')->orderBy('created_at','desc')->paginate(10);
        return view('admin.rotation-evaluations.index', compact('evaluations'));
    }

    // Store new evaluation
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array|min:1',
            'sections.*.section_title' => 'required|string|max:255',
            'sections.*.section_type' => 'required|string',
            'sections.*.subitems' => 'required|array|min:1',
            'sections.*.subitems.*.subitem_text' => 'required|string',
            'sections.*.subitems.*.input_type' => 'required|string',
            'sections.*.subitems.*.scale_desc' => 'required_if:sections.*.subitems.*.input_type,scale_5_with_desc|array|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'errors'=>$validator->errors()],422);
        }

        \DB::beginTransaction();
        try {
            $evaluation = RotationEvaluation::create([
                'title' => $request->title,
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            foreach ($request->sections as $index => $sectionData) {
                $section = RotationEvaluationSection::create([
                    'rotation_evaluation_id' => $evaluation->id,
                    'section_title' => $sectionData['section_title'],
                    'section_type' => $sectionData['section_type'],
                    'description' => $sectionData['description'] ?? null,
                    'options' => !empty($sectionData['options']) ? json_encode($sectionData['options']) : null,
                    'order' => $index,
                ]);

                foreach ($sectionData['subitems'] as $subIndex => $subitemData) {
                    RotationEvaluationSubitem::create([
                        'section_id' => $section->id,
                        'subitem_text' => $subitemData['subitem_text'],
                        'input_type' => $subitemData['input_type'] ?? 'text',
                        'scale_desc' => !empty($subitemData['scale_desc']) ? json_encode($subitemData['scale_desc']) : null,
                        'order' => $subIndex,
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true, 'message'=>'Rotation evaluation form created successfully!']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success'=>false, 'message'=>$e->getMessage()]);
        }
    }

    // Edit evaluation
    public function edit($id)
    {
        $evaluation = RotationEvaluation::with(['sections.subitems'])->findOrFail($id);

        // decode JSON fields
        foreach ($evaluation->sections as $section) {
            if(!empty($section->options)) $section->options = json_decode($section->options, true);
            foreach ($section->subitems as $subitem) {
                if(!empty($subitem->scale_desc)) $subitem->scale_desc = json_decode($subitem->scale_desc,true);
            }
        }

        return response()->json($evaluation);
    }

    // Update evaluation
    public function update(Request $request, $id)
    {
        $evaluation = RotationEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array|min:1',
            'sections.*.section_title' => 'required|string|max:255',
            'sections.*.section_type' => 'required|string',
            'sections.*.subitems' => 'required|array|min:1',
            'sections.*.subitems.*.subitem_text' => 'required|string',
            'sections.*.subitems.*.input_type' => 'required|string',
            'sections.*.subitems.*.scale_desc' => 'required_if:sections.*.subitems.*.input_type,scale_5_with_desc|array|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'errors'=>$validator->errors()],422);
        }

        \DB::beginTransaction();
        try {
            $evaluation->update([
                'title' => $request->title,
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            // Delete old sections/subitems
            foreach($evaluation->sections as $section){
                $section->subitems()->delete();
            }
            $evaluation->sections()->delete();

            // Recreate sections/subitems
            foreach ($request->sections as $index => $sectionData) {
                $section = RotationEvaluationSection::create([
                    'rotation_evaluation_id' => $evaluation->id,
                    'section_title' => $sectionData['section_title'],
                    'section_type' => $sectionData['section_type'],
                    'description' => $sectionData['description'] ?? null,
                    'options' => !empty($sectionData['options']) ? json_encode($sectionData['options']) : null,
                    'order' => $index,
                ]);

                foreach ($sectionData['subitems'] as $subIndex => $subitemData) {
                    RotationEvaluationSubitem::create([
                        'section_id' => $section->id,
                        'subitem_text' => $subitemData['subitem_text'],
                        'input_type' => $subitemData['input_type'] ?? 'text',
                        'scale_desc' => !empty($subitemData['scale_desc']) ? json_encode($subitemData['scale_desc']) : null,
                        'order' => $subIndex,
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true, 'message'=>'Evaluation updated successfully']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success'=>false, 'message'=>$e->getMessage()]);
        }
    }

    // Show evaluation
    public function show($id)
    {
        $evaluation = RotationEvaluation::with(['sections.subitems'])->findOrFail($id);

        foreach ($evaluation->sections as $section) {
            if(!empty($section->options)) $section->options = json_decode($section->options,true);
            foreach($section->subitems as $subitem){
                if(!empty($subitem->scale_desc)) $subitem->scale_desc = json_decode($subitem->scale_desc,true);
            }
        }

        $existingResponse = RotationEvaluationResponse::where([
            'rotation_evaluation_id'=>$id,
            'user_id'=>auth()->id()
        ])->first();

        if($existingResponse) $existingResponse->data = json_decode($existingResponse->responses,true);

        return view('admin.rotation-evaluations.show', compact('evaluation','existingResponse'));
    }

    // Delete evaluation
    public function destroy($id)
    {
        $evaluation = RotationEvaluation::findOrFail($id);
        foreach($evaluation->sections as $section){
            $section->subitems()->delete();
        }
        $evaluation->sections()->delete();
        $evaluation->delete();

        return response()->json(['success'=>true,'message'=>'Deleted successfully']);
    }

    // Submit user response
    public function submitResponse(Request $request, $id)
    {
        $responses = $request->responses ?? [];
        if($request->general_comments) $responses['general_comments'] = $request->general_comments;

        RotationEvaluationResponse::updateOrCreate([
            'rotation_evaluation_id'=>$id,
            'user_id'=>auth()->id()
        ],[
            'responses'=>json_encode($responses),
            'submitted_at'=>now()
        ]);

        return redirect()->route('rotation-evaluations.show',$id)->with('success','Evaluation submitted successfully');
    }

    // View responses
    public function viewResponses($id)
    {
        $evaluation = RotationEvaluation::findOrFail($id);
        $responses = RotationEvaluationResponse::where('rotation_evaluation_id',$id)
            ->latest()->paginate(20);
        return view('admin.rotation-evaluations.responses', compact('evaluation','responses'));
    }
}