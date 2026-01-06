<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
class TraineeExamController extends Controller
{
     public function index()
    {
        $exams = Exam::whereHas('invitations',function($q){
            $q->where('user_id',auth()->id());
        })->paginate(10);

        return view('trainee.exams.index',compact('exams'));
    }

    public function start(Exam $exam)
    {
        ExamAttempt::firstOrCreate(
            [
                'exam_id'=>$exam->id,
                'user_id'=>auth()->id()
            ],
            [
                'created_at'=>now(),
                'status'=>'Started'
            ]
        );

        return redirect()->route('trainee.exams');
    }

    public function submit(Request $request, Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id',$exam->id)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        $attempt->update([
            'obtained_marks'=>$request->marks, // calculated earlier
            'status'=>'Completed',
            // 'submitted_at'=>now()
        ]);

        return redirect()->route('trainee.exams')
            ->with('success','Exam Submitted');
    }
}
