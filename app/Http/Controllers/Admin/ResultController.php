<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function pending()
    {
        $exams = Exam::whereDate('exam_date','<=',now())
            ->with('result')
            ->paginate(10);

        return view('admin.results.pending',compact('exams'));
    }

    public function calculate(Exam $exam)
    {
        $attempts = $exam->attempts()
            ->where('status','Completed');

        ExamResult::updateOrCreate(
            ['exam_id'=>$exam->id],
            [
                'total_students'=>$attempts->count(),
                'passed_students'=>$attempts->where('obtained_marks','>=',50)->count(),
                'failed_students'=>$attempts->where('obtained_marks','<',50)->count(),
                'highest_marks'=>$attempts->max('obtained_marks'),
                'average_marks'=>round($attempts->avg('obtained_marks')),
                'is_calculated'=>1
            ]
        );

        return back()->with('success','Result Calculated');
    }

    public function announce(Exam $exam)
    {
        $exam->result->update([
            'is_announced'=>1,
            'announced_at'=>now()
        ]);

        return back()->with('success','Result Announced');
    }

    public function view(Exam $exam)
    {
        return view('admin.results.view',[
            'exam'=>$exam,
            'result'=>$exam->result,
            'attempts'=>$exam->attempts()->with('user')->paginate(20)
        ]);
    }
}
