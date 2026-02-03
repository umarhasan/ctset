<?php

namespace App\Http\Controllers\CVBuilder;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvDocument;
use App\Models\CvActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cv_id' => 'required|exists:cvs,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        $cv = Cv::findOrFail($request->cv_id);

        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cvdocs', $fileName, 'public');
        }

        $document = CvDocument::create([
            'cv_id' => $cv->id,
            'title' => $request->title,
            'file' => $fileName
        ]);

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'Document "' . $request->title . '" uploaded'
        ]);

        return redirect()->route('cv.edit', $cv->id)
            ->with('success', 'Document uploaded successfully!');
    }

    public function update(Request $request, CvDocument $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);
        $cv = $document->cv;
        $data = ['title' => $request->title];
        if ($request->hasFile('file')) {

            // Delete old file
            if ($document->file && Storage::disk('public')->exists('cvdocs/'.$document->file)) {
                Storage::disk('public')->delete('cvdocs/'.$document->file);
            }

            // Store new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cvdocs', $fileName, 'public');

            $data['file'] = $fileName;
        }

        $document->update($data);

        CvActivity::create([
            'cv_id' => $cv->id,
            'activity' => 'Document "' . $request->title . '" updated'
        ]);

        return redirect()->route('cv.edit', $cv->id)
            ->with('success', 'Document updated successfully!');
    }

    public function destroy($id)
    {
        $document = CvDocument::find($id);
        $cvId = $document->cv_id;
        $title = $document->title;

        if ($document->file && Storage::disk('public')->exists('cvdocs/'.$document->file)) {
            Storage::disk('public')->delete('cvdocs/'.$document->file);
        }

        $document->delete();

        CvActivity::create([
            'cv_id' => $cvId,
            'activity' => 'Document "' . $title . '" deleted'
        ]);

        return redirect()->route('cv.edit', $cvId)
            ->with('success', 'Document deleted successfully!');
    }

    public function streamDocument($filename)
    {
        $filePath = 'cvdocs/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Document not found');
        }

        return response()->file(storage_path('app/public/' . $filePath));
    }

    public function download(CvDocument $document)
    {
        $filePath = storage_path('app/public/cvdocs/' . $document->file);
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }
        return response()->download($filePath, $document->title . '.' . pathinfo($document->file, PATHINFO_EXTENSION));
    }
}
