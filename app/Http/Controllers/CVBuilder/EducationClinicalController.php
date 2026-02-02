<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvEducationClinical;
use App\Models\CvActivity;
use Illuminate\Http\Request;

class EducationClinicalController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'cv_id' => 'required|exists:cvs,id',
            'type' => 'required|in:education,clinical',
            'title' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'year_from' => 'required|integer',
            'year_to' => 'nullable|integer',
            'details' => 'nullable|string'
        ]);

        $cv = Cv::findOrFail($request->cv_id);

        $entry = CvEducationClinical::create($request->all());

        $activityType = $request->type == 'education' ? 'Education' : 'Clinical experience';
        CvActivity::create([
            'cv_id' => $request->cv_id,
            'activity' => $activityType . ' "' . $request->title . '" added'
        ]);

        return redirect()->route('cv.edit', $request->cv_id)
            ->with('success', $activityType . ' added successfully!');
    }

    public function update(Request $request, $id)
    {
        $educationClinical = CvEducationClinical::find($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'institute' => 'required|string|max:255',
            'year_from' => 'required|integer',
            'year_to' => 'nullable|integer',
            'details' => 'nullable|string'
        ]);

        $cv = $educationClinical->cv;

        $educationClinical->update($request->all());

        $activityType = $educationClinical->type == 'education' ? 'Education' : 'Clinical experience';
        CvActivity::create([
            'cv_id' => $educationClinical->cv_id,
            'activity' => $activityType . ' "' . $request->title . '" updated'
        ]);

        return redirect()->route('cv.edit', $educationClinical->cv_id)
            ->with('success', $activityType . ' updated successfully!');
    }

    public function destroy(CvEducationClinical $educationClinical)
    {
        $cv = $educationClinical->cv;
        
        $type = $educationClinical->type;
        $title = $educationClinical->title;
        $cvId = $educationClinical->cv_id;
        
        $educationClinical->delete();

        $activityType = $type == 'education' ? 'Education' : 'Clinical experience';
        CvActivity::create([
            'cv_id' => $cvId,
            'activity' => $activityType . ' "' . $title . '" deleted'
        ]);

        return redirect()->route('cv.edit', $cvId)
            ->with('success', $activityType . ' deleted successfully!');
    }
}