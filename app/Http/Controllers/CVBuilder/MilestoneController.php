<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvMilestone;
use App\Models\CvActivity;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cv_id' => 'required|exists:cvs,id',
            'title' => 'required|string|max:255',
            'month_year' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        $cv = Cv::findOrFail($request->cv_id);

        $milestone = CvMilestone::create($request->all());

        CvActivity::create([
            'cv_id' => $request->cv_id,
            'activity' => 'Milestone "' . $request->title . '" added'
        ]);

        return redirect()->route('cv.edit', $request->cv_id)
            ->with('success', 'Milestone added successfully!');
    }

    public function update(Request $request, CvMilestone $milestone)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'month_year' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        $cv = $milestone->cv;
 
        $milestone->update($request->all());

        CvActivity::create([
            'cv_id' => $milestone->cv_id,
            'activity' => 'Milestone "' . $request->title . '" updated'
        ]);

        return redirect()->route('cv.edit', $milestone->cv_id)
            ->with('success', 'Milestone updated successfully!');
    }

    public function destroy(CvMilestone $milestone)
    {
        $cv = $milestone->cv;
        
        $title = $milestone->title;
        $cvId = $milestone->cv_id;
        
        $milestone->delete();

        CvActivity::create([
            'cv_id' => $cvId,
            'activity' => 'Milestone "' . $title . '" deleted'
        ]);

        return redirect()->route('cv.edit', $cvId)
            ->with('success', 'Milestone deleted successfully!');
    }
}