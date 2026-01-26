<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index()
    {
        $diagnoses = Diagnosis::latest()->get();
        return view('admin.diagnoses.index', compact('diagnoses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Diagnosis::create($data);

        return response()->json(['success' => true]);
    }

    public function edit(Diagnosis $diagnosis)
    {
        return response()->json($diagnosis);
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $diagnosis->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return response()->json(['success' => true]);
    }
}
