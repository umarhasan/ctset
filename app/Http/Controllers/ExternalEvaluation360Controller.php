<?php
namespace App\Http\Controllers;

use App\Models\Evaluation360FormShare;
use App\Models\Evaluation360Response;
use Illuminate\Http\Request;

class ExternalEvaluation360Controller extends Controller
{
    // Show form to external evaluator
    public function show(Evaluation360FormShare $share)
    {
        if($share->status === 'I') abort(403,'Form Locked');

        $form = $share->form()->with('sections')->first();
        $responses = $share->responses->keyBy('section_id');

        return view('external.360-form', compact('share','form','responses'));
    }

    // Save partial responses
    public function save(Request $request, Evaluation360FormShare $share)
    {
        if($share->status === 'I') abort(403);

        foreach($request->responses as $sectionId => $data){
            Evaluation360Response::updateOrCreate(
                [
                    'share_id'=>$share->id,
                    'section_id'=>$sectionId
                ],
                $data
            );
        }

        return response()->json(['success'=>true]);
    }

    // Submit final responses
    public function submit(Evaluation360FormShare $share)
    {
        $share->update([
            'status'=>'I',
            'locked_at'=>now()
        ]);

        return view('external.thank-you');
    }
}
