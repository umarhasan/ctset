<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pdf;

class PdfController extends Controller
{
    public function index()
    {
        $pdfs = Pdf::orderBy('page_name')->get();
        return view('admin.pdfs.index', compact('pdfs'));
    }

    public function store(Request $request)
    {
        foreach ($request->pdfs as $row) {

            $data = [
                'title' => $row['title'],
                'page_key' => $row['page_key'],
                'page_name' => $row['page_name'],
                'total_pages' => $row['total_pages'] ?? null,
                'status' => $row['status'] ?? 1,
            ];

            if(!empty($row['file'])){
                $filePath = $row['file']->store('pdfs','public');
                $data['file'] = $filePath;
            }

            Pdf::updateOrCreate(
                ['id' => $row['id'] ?? null],
                $data
            );
        }

        return redirect()->back()->with('success','PDFs saved successfully');
    }
    
    
    public function delete($id)
    {
        $pdf = Pdf::findOrFail($id);
        if($pdf->file){
            Storage::disk('public')->delete($pdf->file);
        }
        $pdf->delete();
        return response()->json(['success'=>true,'message'=>'PDF deleted successfully']);
    }
}
