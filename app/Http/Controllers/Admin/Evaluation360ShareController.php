<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation360FormShare;
use Illuminate\Http\Request;

class Evaluation360ShareController extends Controller
{
    public function store(Request $request, $formId)
    {

        $share = Evaluation360FormShare::create([
            'evaluation_360_form_id'=>$formId,
            'shared_by'=>auth()->id(),
            'assigned_to'=>$request->student_id ?? null,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'details'=>$request->details,
            'pin'=>rand(1000,9999),
            'status'=>'W'
        ]);

        return response()->json([
            'success'=>true,
            'link'=>url('360/evaluation/'.$share->id)
        ]);
    }

    public function approve($id)
    {
        Evaluation360FormShare::findOrFail($id)->update(['status'=>'A']);
        return back()->with('success','Approved');
    }

    public function unlock($id)
    {
        Evaluation360FormShare::findOrFail($id)->update([
            'status'=>'U',
            'locked_at'=>null
        ]);
        return back()->with('success','Unlocked');
    }
}
