<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
use Illuminate\Support\Facades\Storage;

class TraineeDashboardController extends Controller
{
    public function index()
    {
        return view('trainee.dashboard');
    }

    /**
     * Show PDF viewer for a page
     */
    public function show($page_name, $page_key, $file = 1)
    {
        $pdf = Pdf::active()
            ->where('title', $page_name)
            ->where('page_key', $page_key)
            ->firstOrFail();

        // Single PDF path
        $pdfPath = $pdf->file;

        // If split PDFs: base path
        $pdfBasePath = $pdf->file ? $pdf->title . '_' : '';
        $totalPages  = $pdf->total_pages ?? 1;

        return view('trainee.pdfs.show', compact(
            'pdf',
            'page_name',
            'page_key',
            'pdfPath',
            'pdfBasePath',
            'file',
            'totalPages'
        ));
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
