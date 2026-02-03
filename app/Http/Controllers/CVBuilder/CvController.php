<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvProfile;
use App\Models\CvActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CvController extends Controller
{

    public function index()
    {
        $cvs = Cv::where('user_id', Auth::id())
            ->with(['profile', 'educations', 'clinicals', 'researches', 'awards', 'documents','user'])
            ->latest()
            ->paginate(12);
        
        return view('cvbuilder.cv.index', compact('cvs'));
    }

    public function create()
    {
        return view('cvbuilder.cv.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'primary_speciality' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'template' => 'required|in:template1,template2'
        ]);

        $cv = Cv::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'primary_speciality' => $request->primary_speciality,
            'summary' => $request->summary,
            'template' => $request->template,
            'completeness' => 10, // Start with 10% for basic info
            'is_public' => false,
            'share_token' => null
        ]);

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'CV "' . $cv->title . '" created'
        ]);

        return redirect()->route('cv.edit', $cv->id)
            ->with('success', 'CV created successfully! You can now add more details.');
    }

    public function show(Cv $cv)
    {
        $cv->load(['profile', 'educations', 'clinicals', 'researches', 'awards', 'documents', 'milestones']);
        return view('cvbuilder.cv.show', compact('cv'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cv $cv)
    {
        $cv->load(['profile', 'educations', 'clinicals', 'researches', 'awards', 'documents', 'milestones']);
        $sections = [
            'profile' => $cv->profile ? 100 : 0,
            'education' => min(count($cv->educations) * 20, 100),
            'clinical' => min(count($cv->clinicals) * 25, 100),
            'research' => min(count($cv->researches) * 33, 100),
            'awards' => min(count($cv->awards) * 33, 100),
            'documents' => min(count($cv->documents) * 50, 100),
            'summary' => $cv->summary ? 100 : 0,
        ];
        
        $completeness = round(array_sum($sections) / count($sections));
        
        $cv->update(['completeness' => $completeness]);        
        return view('cvbuilder.cv.edit', compact('cv', 'completeness'));
    }


    public function update(Request $request, Cv $cv)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'primary_speciality' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'template' => 'required|in:template1,template2',
            'is_public' => 'boolean'
        ]);

        $cv->update([
            'title' => $request->title,
            'primary_speciality' => $request->primary_speciality,
            'summary' => $request->summary,
            'template' => $request->template,
            'is_public' => $request->has('is_public')
        ]);

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'CV "' . $cv->title . '" updated'
        ]);

        // Return JSON for AJAX
        if($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('cv.edit', $cv->id)
            ->with('success', 'CV updated successfully!');
    }

    public function destroy(Cv $cv)
    {
        
        $title = $cv->title;
        $cv->delete();

        return redirect()->route('cv.index')
            ->with('success', 'CV "' . $title . '" deleted successfully!');
    }

    public function preview($id)
    {
        $cv = Cv::with(['profile', 'educations', 'clinicals', 'researches', 'awards'])
            ->findOrFail($id);
        
        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'CV previewed'
        ]);
        
        return view('cvbuilder.templates.' . $cv->template, compact('cv'));
    }

     public function pdf($id)
    {
        $cv = Cv::with([
            'profile',
            'educations',
            'clinicals',
            'researches',
            'awards',
            'user'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('cvbuilder.templates.template1', compact('cv'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('CV_'.$cv->id.'.pdf');
    }

    public function share(Request $request, $id)
    {
        $cv = Cv::findOrFail($id);
        
        $cv->update([
            'share_token' => Str::random(40),
            'is_public' => true
        ]);

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'Share link generated'
        ]);

        return back()->with('success', 'Share link generated!')
            ->with('link', route('cv.public', $cv->share_token));
    }

    public function publicView($token)
    {
        $cv = Cv::with(['profile', 'educations', 'clinicals', 'researches', 'awards'])
            ->where('share_token', $token)
            ->where('is_public', true)
            ->firstOrFail();
        
        return view('cvbuilder.templates.' . $cv->template, compact('cv'));
    }
}