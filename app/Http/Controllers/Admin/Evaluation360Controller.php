<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation360Form;
use App\Models\Evaluation360Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Evaluation360Controller extends Controller
{
    /**
     * Display a listing of evaluation forms.
     */
    public function index()
    {
        // Return JSON if AJAX, or Blade if normal
        $evaluations = Evaluation360Form::with('sections')->orderBy('id','desc')->get();

        if(request()->ajax()) {
            return response()->json($evaluations);
        }

        return view('admin.evaluation-360.index');
    }

    /**
     * Store a newly created evaluation form.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.subtitle' => 'nullable|string|max:255',
            'sections.*.col_1_5' => 'nullable|string|max:255',
            'sections.*.col_6_7' => 'nullable|string|max:255',
            'sections.*.ue' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        \DB::beginTransaction();
        try {
            $evaluation = Evaluation360Form::create([
                'title' => $request->title,
                'status' => $request->status ?? 'active'
            ]);

            if($request->has('sections')){
                foreach($request->sections as $index => $section){
                    Evaluation360Section::create([
                        'evaluation_360_form_id' => $evaluation->id,
                        'section_title' => $section['title'],
                        'subtitle' => $section['subtitle'] ?? '',
                        'col_1_5' => $section['col_1_5'] ?? '',
                        'col_6_7' => $section['col_6_7'] ?? '',
                        'ue' => $section['ue'] ?? '',
                        'order' => $index,
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true,'message'=>'Form created successfully','evaluation'=>$evaluation]);
        } catch(\Exception $e){
            \DB::rollback();
            return response()->json(['success'=>false,'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Show the form for editing a specific evaluation.
     */
    public function edit($id)
    {
        $evaluation = Evaluation360Form::with('sections')->findOrFail($id);
        return response()->json($evaluation);
    }

    /**
     * Update the specified evaluation form.
     */
    public function update(Request $request, $id)
    {
        $evaluation = Evaluation360Form::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.subtitle' => 'nullable|string|max:255',
            'sections.*.col_1_5' => 'nullable|string|max:255',
            'sections.*.col_6_7' => 'nullable|string|max:255',
            'sections.*.ue' => 'nullable|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        \DB::beginTransaction();
        try{
            $evaluation->update([
                'title' => $request->title,
                'status' => $request->status
            ]);

            // Delete old sections
            $evaluation->sections()->delete();

            // Add new sections
            if($request->has('sections')){
                foreach($request->sections as $index => $section){
                    Evaluation360Section::create([
                        'evaluation_360_form_id' => $evaluation->id,
                        'section_title' => $section['title'],
                        'subtitle' => $section['subtitle'] ?? '',
                        'col_1_5' => $section['col_1_5'] ?? '',
                        'col_6_7' => $section['col_6_7'] ?? '',
                        'ue' => $section['ue'] ?? '',
                        'order' => $index,
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true,'message'=>'Form updated successfully','evaluation'=>$evaluation]);
        } catch(\Exception $e){
            \DB::rollback();
            return response()->json(['success'=>false,'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified evaluation form.
     */
    public function destroy($id)
    {
        $evaluation = Evaluation360Form::findOrFail($id);
        $evaluation->delete();

        return response()->json(['success'=>true,'message'=>'Form deleted successfully']);
    }
}
