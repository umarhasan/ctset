<?php
namespace App\Http\Controllers;

use App\Models\Evaluation360FormShare;
use App\Models\Evaluation360Response;
use Illuminate\Http\Request;

class ExternalEvaluation360Controller extends Controller
{
    public function show(Request $request, Evaluation360FormShare $share)
    {
        // dd($share);
        if($share->status === 'I') abort(403,'Form Locked');

        // PIN check optional
        if($share->pin && $request->pin != $share->pin){

            return view('external.enter-pin', compact('share'));
        }

        $form = $share->form()->with('sections')->first();
        $responses = $share->responses->keyBy('section_id');

        return view('external.360-form', compact('share','form','responses'));
    }

    public function save(Request $request, Evaluation360FormShare $share)
    {
        if($share->status === 'I') abort(403);

        foreach($request->responses as $sectionId => $data){
            Evaluation360Response::updateOrCreate(
                ['share_id'=>$share->id,'section_id'=>$sectionId],
                $data
            );
        }

        return response()->json(['success'=>true]);
    }
    public function enterPinView($share)
    {
        return view('external.enter-pin', ['shareId' => $share]);
    }

    public function checkPin(Request $request, $share)
    {
        $share = Evaluation360FormShare::findOrFail($share);

        if($share->pin != $request->pin){
            return redirect()->back()->with('error','Invalid PIN!');
        }

        return view('external.evaluation.show', ['share' => $share]);
    }
    public function submit(Evaluation360FormShare $share)
    {
        $share->update(['status'=>'I','locked_at'=>now()]);
        return view('external.thank-you');
    }
}
