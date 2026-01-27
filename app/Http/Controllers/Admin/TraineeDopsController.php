<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DopsAttempt;
use App\Models\Rotation;
use App\Models\Diagnosis;
use App\Models\Procedure;
use App\Models\Dops;
use App\Models\DopsLevel;
use App\Models\DopsRating;
use App\Models\DopsRotation;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\TraineeDopsExport;
use App\Models\DopsCompetencyDefinition;
use App\Models\DopsCompetencyDefinitionDetail;

class TraineeDopsController extends Controller
{
    public function index()
    {
        $rotations = Rotation::all();
        $diagnosis = Diagnosis::get();
        $procedure = Procedure::get();

        $traineeDops = DopsAttempt::with(['rotation','diagnosis','procedure','dops','trainee'])
            ->where('trainee_id', auth()->id())
            ->latest()
            ->get();

        return view('admin.trainee.dops.index', compact('rotations','diagnosis','procedure','traineeDops'));
    }


    // AJAX: rotation → dops
    public function getDops($rotation)
    {
        $dopsIds = DopsRotation::where('rotationid',$rotation)->pluck('dopsid');

        return Dops::whereIn('id',$dopsIds)
            ->select('id','title')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'rotation_id'=>'required',
            'dops_id'=>'required',
            'date'=>'required',
            'from_time'=>'required'
        ]);

        DopsAttempt::create([
            'trainee_id' => auth()->id(),
            'rotation_id'=> $request->rotation_id,
            'dops_id'    => $request->dops_id,
            'date'       => $request->date,
            'from_time'  => $request->from_time,
            'diagnosis'  => json_encode($this->mapInputs(
                                $request->diagnosis_name,
                                $request->diagnosis_value
                            )),
            'procedure'  => json_encode($this->mapInputs(
                                $request->procedure_name,
                                $request->procedure_value
                            )),
            'comments'   => $request->comments,
            'status'     => 'W'
        ]);

        return back()->with('success','DOPS Added Successfully');
    }

    public function edit($id)
    {
        $d = DopsAttempt::findOrFail($id);

        return response()->json([
            'id'=>$d->id,
            'rotation_id'=>$d->rotation_id,
            'dops_id'=>$d->dops_id,
            'date'=>$d->date,
            'from_time'=>$d->from_time,
            'diagnosis'=>json_decode($d->diagnosis),
            'procedure'=>json_decode($d->procedure),
            'comments'=>$d->comments
        ]);
    }

    public function update(Request $request,$id)
    {
        $d = DopsAttempt::findOrFail($id);

        $d->update([
            'rotation_id'=>$request->rotation_id,
            'dops_id'=>$request->dops_id,
            'date'=>$request->date,
            'from_time'=>$request->from_time,
            'diagnosis'=>json_encode($this->mapInputs(
                $request->diagnosis_name,
                $request->diagnosis_value
            )),
            'procedure'=>json_encode($this->mapInputs(
                $request->procedure_name,
                $request->procedure_value
            )),
            'comments'=>$request->comments
        ]);

        return back()->with('success','DOPS Updated Successfully');
    }

    // public function show($id)
    // {

    //     $dops = Dops::findOrFail($id);

    //     $levels = DopsLevel::with('levels')->where('dopsid', $dops->id)->get();

    //     $ratings = DopsRating::with('ratings')->where('dropsid', $dops->id)->get();
    //     $competencies = DopsCompetencyDefinition::with('definitions')
    //         ->where('dopsid', $dops->id)
    //         ->get();

    //     return view('admin.trainee.dops.show', compact(
    //         'dops',
    //         'levels',
    //         'ratings',
    //         'competencies'
    //     ));
    // }

    public function show($id)
    {
        $dops = Dops::with([
            'Dopsattempt',
            'Dopsattempt.trainee',
            'levels.level',
            'ratings.rating',

        ])->findOrFail($id);

        $competencies = DopsCompetencyDefinition::with('definitions','competencies')
            ->where('dopsid', $id)
            ->get();

        return view('admin.trainee.dops.show', compact('dops','competencies'));
    }

    public function destroy($id)
    {
        DopsAttempt::findOrFail($id)->delete();
        return back()->with('success','DOPS Deleted');
    }

    private function mapInputs($names,$values)
    {
        if(!$names) return [];

        $out=[];
        foreach($names as $i=>$n){
            $out[]=[
                'name'=>$n,
                'value'=>$values[$i] ?? ''
            ];
        }
        return $out;
    }

    public function end(DopsAttempt $traine_dops)
    {
        $traine_dops->update([
            'to_time' => now()->format('H:i')
        ]);

        return back()->with('success','Activity Ended');
    }

    public function exportExcel()
    {
        return Excel::download(
            new TraineeDopsExport,
            'trainee-dops-'.date('Y-m-d').'.xlsx'
        );
    }

    public function exportPdf()
    {
        $attempts = DopsAttempt::with(['dops'])->get();
        $pdf = Pdf::loadView('admin.trainee.dops.pdf', compact('attempts'));
        return $pdf->download('trainee-dops.pdf');
    }
}
