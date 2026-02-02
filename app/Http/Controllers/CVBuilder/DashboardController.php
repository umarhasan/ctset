<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvActivity;
use App\Models\CvMilestone;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cvs = $user->cvs()->withCount(['educations', 'clinicals', 'researches', 'awards', 'documents','milestone'])->get();

        $educationCount = $cvs->sum('educations_count');
        $clinicalCount = $cvs->sum('clinicals_count');
        $researchCount = $cvs->sum('researches_count');
        $awardCount = $cvs->sum('awards_count');
        $documentsCount = $cvs->sum('documents_count');
        $cvCount = $cvs->count();

        $activityCvIds = $cvs->pluck('id');
        $activities = CvActivity::whereIn('cv_id', $activityCvIds)
            ->latest()
            ->take(5)
            ->get();

        $milestones = CvMilestone::whereIn('cv_id', $activityCvIds)
        ->orderBy('created_at', 'asc') // order by creation date
        ->take(5)
        ->get();
        return view('cvbuilder.dashboard', [
            'educationCount' => $educationCount,
            'clinicalCount' => $clinicalCount,
            'researchCount' => $researchCount,
            'awardCount' => $awardCount,
            'documentsCount' => $documentsCount,
            'cvCount' => $cvCount,
            'activities' => $activities,
            'cvs' => $cvs,
            'milestones' => $milestones
        ]);
    }
}