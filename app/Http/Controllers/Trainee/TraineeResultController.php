<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamResult;
use App\Models\Exam;

class TraineeResultController extends Controller
{
    public function index()
    {
        $results = ExamResult::where('is_announced',1)
            ->whereHas('exam.invitations',function($q){
                $q->where('user_id',auth()->id());
            })->paginate(10);

        return view('trainee.results.index',compact('results'));
    }

    public function view(Exam $exam)
    {
        abort_if(!$exam->result || !$exam->result->is_announced,403);

        $attempt = $exam->attempts()
            ->where('user_id',auth()->id())
            ->first();

        return view('trainee.results.view',[
            'exam'=>$exam,
            'result'=>$exam->result,
            'attempt'=>$attempt
        ]);
    }
}
