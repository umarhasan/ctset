<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TraineeEvaluation;
use Illuminate\Http\Request;

class TraineeEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = TraineeEvaluation::with('sections')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.trainee-evaluations.index', compact('evaluations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.section_title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $evaluation = TraineeEvaluation::create([
                'title' => $request->title,
                'status' => $request->status,
            ]);

            if ($request->has('sections')) {
                foreach ($request->sections as $index => $section) {
                    TraineeEvaluationSection::create([
                        'trainee_evaluation_id' => $evaluation->id,
                        'section_title' => $section['section_title'],
                        'order' => $index,
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trainee evaluation form created successfully!',
                'evaluation' => $evaluation
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $evaluation = TraineeEvaluation::with('sections')->findOrFail($id);
        return response()->json($evaluation);
    }

    public function update(Request $request, $id)
    {
        $evaluation = TraineeEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.section_title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $evaluation->update([
                'title' => $request->title,
                'status' => $request->status,
            ]);

            // Delete existing sections
            $evaluation->sections()->delete();

            // Create new sections
            if ($request->has('sections')) {
                foreach ($request->sections as $index => $section) {
                    TraineeEvaluationSection::create([
                        'trainee_evaluation_id' => $evaluation->id,
                        'section_title' => $section['section_title'],
                        'order' => $index,
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trainee evaluation form updated successfully!',
                'evaluation' => $evaluation
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $evaluation = TraineeEvaluation::findOrFail($id);
        $evaluation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trainee evaluation form deleted successfully!'
        ]);
    }
}
