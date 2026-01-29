<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation360Form;
use App\Models\Evaluation360Section;
use App\Models\Evaluation360FormShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class Evaluation360Controller extends Controller
{
    // List forms (Admin / Assessor / Trainee)
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('Admin')){
            $evaluations = Evaluation360Form::with('sections')->orderBy('id','desc')->get();
            if(request()->ajax()) return response()->json($evaluations);
            return view('admin.evaluation-360.index');
        }

        if($user->hasRole('Assessor')){
            $assignedForms = $user->assigned360Forms()->with('sections','shares')->get();
            return view('assessor.360-results', compact('assignedForms'));
        }

        if($user->hasRole('Trainee')){
            $completedForms = $user->completed360Forms()->with('sections','shares')->get();
            return view('assessor.360-results', compact('completedForms'));
        }

        abort(403);
    }

    // Create Form
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

            if($request->sections){
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

    // Edit Form
    public function edit($id)
    {
        $evaluation = Evaluation360Form::with('sections')->findOrFail($id);
        return response()->json($evaluation);
    }

    // Update Form
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

        if($validator->fails()) return response()->json(['success'=>false,'errors'=>$validator->errors()],422);

        \DB::beginTransaction();
        try{
            $evaluation->update([
                'title' => $request->title,
                'status' => $request->status
            ]);

            // Delete old sections
            $evaluation->sections()->delete();

            // Add new sections
            if($request->sections){
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

    // Delete Form
    public function destroy($id)
    {
        $evaluation = Evaluation360Form::findOrFail($id);
        $evaluation->delete();
        return response()->json(['success'=>true,'message'=>'Form deleted successfully']);
    }

    // View all responses for a form (Admin)
    public function responses($id)
    {
        $form = Evaluation360Form::findOrFail($id);

        return view('admin.evaluation-360.responses', compact('form'));
    }
}
