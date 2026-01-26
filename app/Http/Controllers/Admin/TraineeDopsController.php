<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DopsAttempt;
use App\Models\Rotation;
use App\Models\Dops;
use App\Models\DopsRotation;

class TraineeDopsController extends Controller
{
    public function index()
    {
        $rotations = Rotation::all();

        $traineeDops = DopsAttempt::with(['rotation','dops','trainee'])
            ->where('trainee_id', auth()->id())
            ->latest()
            ->get();

        return view('trainee.dops.index', compact('rotations','traineeDops'));
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
}
