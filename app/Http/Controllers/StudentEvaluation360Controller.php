<?php
namespace App\Http\Controllers;

use App\Models\Evaluation360FormShare;

class StudentEvaluation360Controller extends Controller
{
    public function index()
    {
        $evaluations = Evaluation360FormShare::where('assigned_to',auth()->id())
            ->where('status','A')
            ->with('form','responses.section')
            ->get();

        return view('trainee.360-results',compact('evaluations'));
    }
}
