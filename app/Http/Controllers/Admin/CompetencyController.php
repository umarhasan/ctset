<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompetencyController extends Controller
{
    public function index()
    {
        $competencies = Competency::ordered()->get();
        return view('admin.competencies.index', compact('competencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:competencies,key',
            'sequence' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Competency::create($validator->validated());
        return response()->json(['success' => true]);
    }

    public function edit(Competency $competency)
    {
        return response()->json($competency);
    }

    public function update(Request $request, Competency $competency)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:competencies,key,' . $competency->id,
            'sequence' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $competency->update($validator->validated());
        return response()->json(['success' => true]);
    }

    public function destroy(Competency $competency)
    {
        $competency->delete();
        return response()->json(['success' => true]);
    }
}
