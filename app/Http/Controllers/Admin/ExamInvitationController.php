<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExamInvitationController extends Controller
{
    public function pendingExams()
    {
        $exams = Exam::with([
                'testType','questionTypes','examDuration'
            ])
            ->withCount([
                'invitations as unregistered_count' => fn ($q) =>
                    $q->where('status', 'Unregistered')
            ])
            ->whereDoesntHave('invitations')
            ->orWhereHas('invitations', fn ($q) =>
                $q->where('status', 'Unregistered')
            )
            ->paginate(10);

        return view('admin.exams.pending', compact('exams'));
    }

    public function sendInvite(Exam $exam)
    {
        $invitedIds = $exam->invitations()->pluck('user_id');

        $trainees = User::role('Trainee')
            ->whereNotIn('id', $invitedIds)
            ->paginate(10);

        return view('admin.exams.send-invite', compact('exam', 'trainees'));
    }

    public function sendInviteAction(Request $request, Exam $exam)
    {
        $request->validate([
            'user_ids' => 'required|array'
        ]);

        foreach ($request->user_ids as $userId) {
            Invitation::firstOrCreate(
                [
                    'exam_id' => $exam->id,
                    'user_id' => $userId,
                ],
                [
                    'status' => 'Unregistered',
                    'sent_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Invitations sent successfully');
    }

    public function sentInvites()
    {
        $exams = Exam::with('testType')
            ->has('invitations')
            ->withCount('invitations')
            ->paginate(10);

        return view('admin.exams.sent-invites', compact('exams'));
    }

    public function viewInvitedStudents($examId,)
    {

        $exam = Exam::findOrFail($examId);

        $status = request('status', 'All');

        $query = $exam->invitations()->with('user');

        if ($status !== 'All') {
            $query->where('status', $status);
        }

        $invitations = $query->paginate(10);

        $statusCounts = [
            'All' => $exam->invitations()->count(),
            'Unregistered' => $exam->invitations()->where('status','Unregistered')->count(),
            'Incompleted' => $exam->invitations()->where('status','Incompleted')->count(),
            'Completed' => $exam->invitations()->where('status','Completed')->count(),
            'Absent' => $exam->invitations()->where('status','Absent')->count(),
        ];

        return view(
            'admin.exams.view-invited-students',
            compact('exam','invitations','status','statusCounts')
        );
    }

    public function updateStatus(Request $request, Invitation $invitation)
    {
        $invitation->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'Completed' ? now() : null
        ]);

        return back()->with('success','Status updated');
    }

     public function myInvitations()
    {
        if (!Auth::user()->hasRole('Trainee')) {
            abort(403, 'Only trainees can access this page.');
        }

        $invitations = Auth::user()->examInvitations()
                         ->with('exam')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('trainee.invitations', compact('invitations'));
    }
}
