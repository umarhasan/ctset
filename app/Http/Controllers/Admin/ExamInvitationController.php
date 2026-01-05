<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ExamInvitationController extends Controller
{
    // Pending Exams for Invitation (exams without any invites or with only unregistered invites)
    public function pendingExams()
    {
        // Get exams that have no invitations or only unregistered invitations
        $exams = Exam::whereDoesntHave('invitations')
                     ->orWhereHas('invitations', function($query) {
                         $query->where('status', 'Unregistered');
                     })
                     ->withCount(['invitations as unregistered_count' => function($query) {
                         $query->where('status', 'Unregistered');
                     }])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('admin.exams.pending', compact('exams'));
    }

    // Send Invite Page - Show trainees not yet invited for this exam
    public function sendInvite($examId)
    {

        $exam = Exam::where('exam_id', $examId)->firstOrFail();

        // Get trainee users not yet invited for this exam
        $invitedUserIds = $exam->invitations->pluck('user_id')->toArray();
        $trainees = User::role('Trainee')
                       ->whereNotIn('id', $invitedUserIds)
                       ->orderBy('name')
                       ->paginate(10);

        return view('admin.exams.sent-invites', compact('exam', 'trainees'));
    }

    // Send Invite Action
    public function sendInviteAction(Request $request, $examId)
    {

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $exam = Exam::where('exam_id', $examId)->firstOrFail();

        DB::transaction(function () use ($exam, $request) {
            foreach ($request->user_ids as $userId) {
                Invitation::create([
                    'exam_id' => $exam->id,
                    'user_id' => $userId,
                    'status' => 'Unregistered',
                    'sent_at' => now()
                ]);
            }
        });

        return redirect()->route('exams.sent-invites')
                         ->with('success', 'Invites sent successfully!');
    }

    // Exams with Sent Invites
    public function sentInvites()
    {
        $exams = Exam::has('invitations')
             ->withCount(['invitations as total_invites'])
             ->with(['invitations' => function($query) {
                 $query->select('exam_id', DB::raw('count(*) as status_count'), 'status')
                       ->groupBy('exam_id', 'status');
             }])
             ->orderBy('created_at', 'desc')
             ->paginate(10);

        return view('admin.exams.sent-invites', compact('exams'));
    }

    // View Invited Students for Specific Exam
    public function viewInvitedStudents($examId)
    {
        $exam = Exam::where('exam_id', $examId)->firstOrFail();

        $status = request('status', 'All');

        $query = $exam->invitations()->with('user');

        if ($status !== 'All') {
            $query->where('status', $status);
        }

        $invitations = $query->paginate(10);

        // Get status counts
        $statusCounts = $exam->status_counts;

        return view('admin.exams.view-invited-students', compact('exam', 'invitations', 'statusCounts', 'status'));
    }

    // Update Invitation Status (if needed for admin to manually update)
    public function updateStatus(Request $request, $invitationId)
    {
        $request->validate([
            'status' => 'required|in:Unregistered,Incompleted,Completed,Absent'
        ]);

        $invitation = Invitation::findOrFail($invitationId);
        $invitation->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'Completed' ? now() : null
        ]);

        return back()->with('success', 'Status updated successfully!');
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
