<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    /**
     * Show all PDFs in management table
     */
    public function index()
    {
        $pdfs = Pdf::orderBy('page_name')->get();
        return view('admin.pdfs.index', compact('pdfs'));
    }

    /**
     * Store or update PDFs
     */
    public function store(Request $request)
    {
        foreach ($request->pdfs as $row) {

            $data = [
                'title'       => $row['title'],
                'page_key'    => $row['page_key'],
                'page_name'   => $row['page_name'],
                'total_pages' => $row['total_pages'] ?? null,
                'status'      => $row['status'] ?? 1,
            ];

            if (!empty($row['file'])) {
                // Save in storage/app/public/pdfs
                $filePath = $row['file']->store('pdfs', 'public');
                $data['file'] = $filePath; // store relative path
            }

            Pdf::updateOrCreate(
                ['id' => $row['id'] ?? null],
                $data
            );
        }

        return redirect()->back()->with('success', 'PDFs saved successfully');
    }

    /**
     * Delete a PDF
     */
    public function delete($id)
    {
        $pdf = Pdf::findOrFail($id);

        // Delete file from storage
        if ($pdf->file && Storage::disk('public')->exists($pdf->file)) {
            Storage::disk('public')->delete($pdf->file);
        }

        $pdf->delete();

        return response()->json([
            'success' => true,
            'message' => 'PDF deleted successfully'
        ]);
    }

    /**
     * Stream PDF from storage
     */
    public function streamPdf($filename)
    {
        $filePath = 'pdfs/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file(storage_path('app/public/' . $filePath));
    }
}
