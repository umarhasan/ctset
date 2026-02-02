<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvResearchAward;
use App\Models\CvActivity;
use Illuminate\Http\Request;

class ResearchAwardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cv_id' => 'required|exists:cvs,id',
            'type' => 'required|in:research,award',
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'details' => 'nullable|string'
        ]);

        $cv = Cv::findOrFail($request->cv_id);

        $entry = CvResearchAward::create($request->all());

        $activityType = $request->type == 'research' ? 'Research' : 'Award';
        CvActivity::create([
            'cv_id' => $request->cv_id,
            'activity' => $activityType . ' "' . $request->title . '" added'
        ]);

        return redirect()->route('cv.edit', $request->cv_id)
            ->with('success', $activityType . ' added successfully!');
    }

    public function update(Request $request, CvResearchAward $researchAward)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'details' => 'nullable|string'
        ]);

        $cv = $researchAward->cv;

        $researchAward->update($request->all());

        $activityType = $researchAward->type == 'research' ? 'Research' : 'Award';
        CvActivity::create([
            'cv_id' => $researchAward->cv_id,
            'activity' => $activityType . ' "' . $request->title . '" updated'
        ]);

        return redirect()->route('cv.edit', $researchAward->cv_id)
            ->with('success', $activityType . ' updated successfully!');
    }

    public function destroy(CvResearchAward $researchAward)
    {
        $cv = $researchAward->cv;
        
        $type = $researchAward->type;
        $title = $researchAward->title;
        $cvId = $researchAward->cv_id;
        
        $researchAward->delete();

        $activityType = $type == 'research' ? 'Research' : 'Award';
        CvActivity::create([
            'cv_id' => $cvId,
            'activity' => $activityType . ' "' . $title . '" deleted'
        ]);

        return redirect()->route('cv.edit', $cvId)
            ->with('success', $activityType . ' deleted successfully!');
    }
}