<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation360Form;
use App\Models\Evaluation360Section;
use App\Models\Evaluation360FormShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class Evaluation360Controller extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('Admin')){

            $evaluations = Evaluation360Form::with('sections')->orderBy('id','desc')->get();

            // All users jinke role Assessor ya Trainee ho
            $users = User::whereHas('roles', function($q){
                $q->whereIn('name', ['Assessor', 'Trainee']);
            })->orderBy('name')->get();

            // Ajax check for table only
            if(request()->ajax()){
                return response()->json($evaluations);
            }
            return view('admin.evaluation-360.index', compact('evaluations','users'));
        }

        if($user->hasRole('Assessor') || $user->hasRole('Trainee')){
            $evaluations = Evaluation360Form::with(['shares'=>function($q) use($user){
                // Assessor/Trainee only see shares assigned to them or themselves
                if($user->hasRole('Assessor')){
                    $q->where('assigned_to', $user->id);
                } else {
                    $q->where('email', $user->email)->orWhere('assigned_to', $user->id);
                }
                $q->with(['sharedBy','assignedTo']);
            },'sections'])->get();

            return view('admin.evaluation-360.index', compact('evaluations'));
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'status'=>'nullable|in:active,inactive',
            'sections'=>'nullable|array',
            'sections.*.title'=>'required|string|max:255',
        ]);

        if($validator->fails()) return response()->json(['success'=>false,'errors'=>$validator->errors()],422);

        DB::beginTransaction();
        try{
            $evaluation = Evaluation360Form::create([
                'title'=>$request->title,
                'status'=>$request->status ?? 'active'
            ]);

            if($request->sections){
                foreach($request->sections as $index => $section){
                    Evaluation360Section::create([
                        'evaluation_360_form_id'=>$evaluation->id,
                        'section_title'=>$section['title'],
                        'subtitle'=>$section['subtitle'] ?? '',
                        'col_1_5'=>$section['col_1_5'] ?? '',
                        'col_6_7'=>$section['col_6_7'] ?? '',
                        'ue'=>$section['ue'] ?? '',
                        'order'=>$index
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true,'evaluation'=>$evaluation]);
        } catch(\Exception $e){
            \DB::rollback();
            return response()->json(['success'=>false,'message'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $evaluation = Evaluation360Form::with('sections')->findOrFail($id);
        return response()->json($evaluation);
    }

    public function update(Request $request, $id)
    {
        $evaluation = Evaluation360Form::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title'=>'required|string|max:255',
            'status'=>'required|in:active,inactive',
            'sections'=>'nullable|array',
            'sections.*.title'=>'required|string|max:255',
        ]);

        if($validator->fails()) return response()->json(['success'=>false,'errors'=>$validator->errors()],422);

        \DB::beginTransaction();
        try{
            $evaluation->update(['title'=>$request->title,'status'=>$request->status]);

            $evaluation->sections()->delete();

            if($request->sections){
                foreach($request->sections as $index => $section){
                    Evaluation360Section::create([
                        'evaluation_360_form_id'=>$evaluation->id,
                        'section_title'=>$section['title'],
                        'subtitle'=>$section['subtitle'] ?? '',
                        'col_1_5'=>$section['col_1_5'] ?? '',
                        'col_6_7'=>$section['col_6_7'] ?? '',
                        'ue'=>$section['ue'] ?? '',
                        'order'=>$index
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success'=>true]);
        } catch(\Exception $e){
            \DB::rollback();
            return response()->json(['success'=>false,'message'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $evaluation = Evaluation360Form::findOrFail($id);
        $evaluation->delete();
        return response()->json(['success'=>true]);
    }

    public function responses($id)
    {
        $form = Evaluation360Form::with('shares.responses.section')->findOrFail($id);
        return view('admin.evaluation-360.responses', compact('form'));
    }


    public function show($id)
    {
        $form = Evaluation360Form::with([
            'sections' => function($q) {
                $q->orderBy('order', 'asc');
            }
        ])->findOrFail($id);
        
        if (request()->has('token')) {
            
            $share = Evaluation360FormShare::with([
                'sharedBy',
                'assignedTo',
                'responses' => function($q) {
                    $q->with('section');
                }
            ])
            ->where('token', request()->token)
            ->where('evaluation_360_form_id', $id)
            ->firstOrFail();
            
            $totalScore = 0;
            $totalQuestions = 0;
            
            foreach ($share->responses as $response) {
                if (is_numeric($response->score)) {
                    $totalScore += (int)$response->score;
                    $totalQuestions++;
                }
            }
            
            $percentage = $totalQuestions > 0 
                ? round(($totalScore / ($totalQuestions * 7)) * 100, 2) 
                : 0;
            
            $grade = $this->calculateGrade($percentage);
            
            return view('evaluation-360.show', compact('form', 'share', 'percentage', 'grade'));
        }
        
        $share = $form->shares()
            ->with([
                'sharedBy',
                'assignedTo',
                'responses' => function($q) {
                    $q->with('section');
                }
            ])
            ->first();
        
        if (!$share) {
            $share = new \stdClass();
            $share->responses = collect([]);
            $share->sharedBy = null;
            $share->assignedTo = null;
            $share->status = 'pending';
            $share->grade = '-';
            $share->rotation = '-';
            $share->score = 0;
        }
        
        $totalScore = 0;
        $totalQuestions = 0;
        
        if ($share && isset($share->responses)) {
            foreach ($share->responses as $response) {
                if (is_numeric($response->score)) {
                    $totalScore += (int)$response->score;
                    $totalQuestions++;
                }
            }
        }
        
        $percentage = $totalQuestions > 0 
            ? round(($totalScore / ($totalQuestions * 7)) * 100, 2) 
            : 0;
        
        $grade = $this->calculateGrade($percentage);
        
        return view('admin.evaluation-360.show', compact('form', 'share', 'percentage', 'grade'));
    }

    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }
}
