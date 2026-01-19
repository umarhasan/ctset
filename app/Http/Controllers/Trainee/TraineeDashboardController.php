<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
class TraineeDashboardController extends Controller
{
    public function index()
    {
        return view('trainee.dashboard');
    }

    public function show($page_name, $page_key, $file = 1)
    {
        $pdf = Pdf::active()
            ->where('title', $page_name)
            ->where('page_key', $page_key)
            ->firstOrFail();

        // If you have a single PDF file
        $pdfPath = $pdf->file; // This should be the full path to your PDF

        // OR if you have split PDFs like in your current setup
        $pdfBasePath = $pdf->file . '/' . $pdf->title . '_';
        $totalPages  = $pdf->total_pages;

        return view('trainee.pdfs.show', compact(
            'pdf',
            'page_name',
            'page_key',
            'pdfPath', // Use this for single PDF
            'pdfBasePath', // Use this for split PDFs
            'file',
            'totalPages'
        ));
    }
}
