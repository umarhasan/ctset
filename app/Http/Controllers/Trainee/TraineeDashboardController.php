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
        $pdf = Pdf::active()->where('title', $page_name)->firstOrFail();
        
        $pdfFileName = pathinfo($pdf->file . '/' . $pdf->title, PATHINFO_FILENAME);
        $pdfBasePath = $pdf->file . '/' . $pdf->title . '_';
        $totalPages = $pdf->total_pages;

        return view('trainee.pdfs.show', compact(
            'pdf', 'page_name', 'pdfBasePath', 'file', 'totalPages'
        ));
    }
}
