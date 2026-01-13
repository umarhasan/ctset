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

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'key' => 'required|unique:competencies,key',
            'sequence' => 'required|integer|min:1',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        Competency::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Competency saved successfully',
        ]);
    }

    public function edit(Competency $competency)
    {
        return response()->json($competency);
    }

    public function update(Request $r, Competency $competency)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'key' => 'required|unique:competencies,key,' . $competency->id,
            'sequence' => 'required|integer|min:1',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $competency->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Competency updated successfully',
        ]);
    }

    public function destroy(Competency $competency)
    {
        try {
            $competency->delete();

            return response()->json([
                'success' => true,
                'message' => 'Competency deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete this record',
            ], 500);
        }
    }
}
