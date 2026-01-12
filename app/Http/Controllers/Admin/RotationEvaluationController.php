<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RotationEvaluation;
use App\Models\RotationEvaluationSection;
use App\Models\RotationEvaluationSubitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RotationEvaluationController extends Controller
{
    public function index()
    {
        $evaluations = RotationEvaluation::with('sections')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.rotation-evaluations.index', compact('evaluations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.section_title' => 'required|string|max:255',
            'sections.*.section_type' => 'required|in:text,yes_no,scale_5,scale_5_with_desc',
            'sections.*.description' => 'nullable|string',
            'sections.*.options' => 'nullable|array',
            'sections.*.subitems' => 'nullable|array',
            'sections.*.subitems.*.subitem_text' => 'nullable|string',
            'sections.*.subitems.*.input_type' => 'nullable|in:text,checkbox,radio',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $evaluation = RotationEvaluation::create([
                'title' => $request->title,
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            if ($request->has('sections')) {
                foreach ($request->sections as $index => $sectionData) {
                    $section = RotationEvaluationSection::create([
                        'rotation_evaluation_id' => $evaluation->id,
                        'section_title' => $sectionData['section_title'],
                        'section_type' => $sectionData['section_type'],
                        'description' => $sectionData['description'] ?? null,
                        'options' => isset($sectionData['options']) ? json_encode($sectionData['options']) : null,
                        'order' => $index,
                    ]);

                    // Create subitems if exist
                    if (isset($sectionData['subitems'])) {
                        foreach ($sectionData['subitems'] as $subIndex => $subitemData) {
                            RotationEvaluationSubitem::create([
                                'section_id' => $section->id,
                                'subitem_text' => $subitemData['subitem_text'],
                                'input_type' => $subitemData['input_type'] ?? 'text',
                                'order' => $subIndex,
                            ]);
                        }
                    }
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rotation evaluation form created successfully!',
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
        $evaluation = RotationEvaluation::with(['sections.subitems'])->findOrFail($id);
        return response()->json($evaluation);
    }

    public function update(Request $request, $id)
    {
        $evaluation = RotationEvaluation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'nullable|array',
            'sections.*.section_title' => 'required|string|max:255',
            'sections.*.section_type' => 'required|in:text,yes_no,scale_5,scale_5_with_desc',
            'sections.*.description' => 'nullable|string',
            'sections.*.options' => 'nullable|array',
            'sections.*.subitems' => 'nullable|array',
            'sections.*.subitems.*.subitem_text' => 'nullable|string',
            'sections.*.subitems.*.input_type' => 'nullable|in:text,checkbox,radio',
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
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            // Delete existing sections and subitems
            $evaluation->sections()->each(function($section) {
                $section->subitems()->delete();
                $section->delete();
            });

            // Create new sections
            if ($request->has('sections')) {
                foreach ($request->sections as $index => $sectionData) {
                    $section = RotationEvaluationSection::create([
                        'rotation_evaluation_id' => $evaluation->id,
                        'section_title' => $sectionData['section_title'],
                        'section_type' => $sectionData['section_type'],
                        'description' => $sectionData['description'] ?? null,
                        'options' => isset($sectionData['options']) ? json_encode($sectionData['options']) : null,
                        'order' => $index,
                    ]);

                    // Create subitems if exist
                    if (isset($sectionData['subitems'])) {
                        foreach ($sectionData['subitems'] as $subIndex => $subitemData) {
                            RotationEvaluationSubitem::create([
                                'section_id' => $section->id,
                                'subitem_text' => $subitemData['subitem_text'],
                                'input_type' => $subitemData['input_type'] ?? 'text',
                                'order' => $subIndex,
                            ]);
                        }
                    }
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rotation evaluation form updated successfully!',
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
        $evaluation = RotationEvaluation::findOrFail($id);
        $evaluation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rotation evaluation form deleted successfully!'
        ]);
    }
}
