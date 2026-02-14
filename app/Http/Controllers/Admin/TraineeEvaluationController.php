<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TraineeEvaluation;
use App\Models\TraineeEvaluationSection;
use App\Models\TraineeEvaluationPoint;
use App\Models\EvaluationPointRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TraineeEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = TraineeEvaluation::with('sections.points')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.trainee-evaluations.index', compact('evaluations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array|min:1',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.points' => 'required|array|min:1',
            'sections.*.points.*' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {

            $evaluation = TraineeEvaluation::create([
                'title' => $request->title,
                'status' => $request->status,
            ]);

            foreach ($request->sections as $index => $section) {

                $evaluationSection = TraineeEvaluationSection::create([
                    'trainee_evaluation_id' => $evaluation->id,
                    'section_title' => $section['title'],
                    'order' => $index,
                ]);

                foreach ($section['points'] as $pointIndex => $point) {

                    TraineeEvaluationPoint::create([
                        'section_id' => $evaluationSection->id,
                        'point_text' => $point,
                        'order' => $pointIndex,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trainee evaluation form created successfully!',
                'evaluation' => $evaluation->load('sections.points')
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $evaluation = TraineeEvaluation::with([
            'sections' => fn($q) => $q->orderBy('order'),
            'sections.points' => fn($q) => $q->orderBy('order')
        ])->findOrFail($id);

        return response()->json($evaluation);
    }

    public function update(Request $request, $id)
    {
        $evaluation = TraineeEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array|min:1',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.points' => 'required|array|min:1',
            'sections.*.points.*' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {

            // update evaluation
            $evaluation->update([
                'title' => $request->title,
                'status' => $request->status,
            ]);

            /**
             * IMPORTANT FIX
             * old ratings delete karna zaroori hai
             * warna section_id mismatch hoga
             */
            EvaluationPointRating::where('evaluation_id', $evaluation->id)->delete();

            // delete old sections & points
            $evaluation->sections()->delete();

            // recreate new structure
            foreach ($request->sections as $index => $section) {

                $evaluationSection = TraineeEvaluationSection::create([
                    'trainee_evaluation_id' => $evaluation->id,
                    'section_title' => $section['title'],
                    'order' => $index,
                ]);

                foreach ($section['points'] as $pointIndex => $point) {

                    TraineeEvaluationPoint::create([
                        'section_id' => $evaluationSection->id,
                        'point_text' => $point,
                        'order' => $pointIndex,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trainee evaluation form updated successfully!',
                'evaluation' => $evaluation->load('sections.points')
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $evaluation = TraineeEvaluation::with([
            'sections' => fn($q) => $q->orderBy('order'),
            'sections.points' => fn($q) => $q->orderBy('order')
        ])->findOrFail($id);

        return view('admin.trainee-evaluations.show', compact('evaluation'));
    }

    public function submitFromShow(Request $request, $id)
    {
        $evaluation = TraineeEvaluation::findOrFail($id);

        foreach ($request->ratings as $pointId => $rating) {

            $point = TraineeEvaluationPoint::find($pointId);

            if (!$point) {
                continue; // prevent crash
            }

            EvaluationPointRating::updateOrCreate(
                [
                    'evaluation_id' => $id,
                    'point_id' => $pointId,
                ],
                [
                    'section_id' => $point->section_id,
                    'rating' => $rating
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Evaluation Submitted Successfully'
        ]);
    }k

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