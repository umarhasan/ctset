<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LongitudinalRequirement;
use App\Models\LongitudinalSection;
use App\Models\LongitudinalSubSection;
use App\Models\LongitudinalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LongitudinalRequirementController extends Controller
{
    public function index()
    {
        $requirements = LongitudinalRequirement::with(['sections' => function($query) {
            $query->orderBy('section_letter');
        }])->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.longitudinal-requirements.index', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create the main requirement
            $requirement = LongitudinalRequirement::create([
                'title' => $request->title,
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            $this->processSections($requirement, $request->sections);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Longitudinal requirement form created successfully!',
                'requirement' => $requirement->load(['sections.subSections.items'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create form: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function edit($id)
    {
        $requirement = LongitudinalRequirement::with([
            'sections.subSections.items' => function($query) {
                $query->orderBy('order');
            },
            'sections' => function($query) {
                $query->orderBy('order');
            }
        ])->findOrFail($id);

        // Transform data for frontend
        $formattedRequirement = [
            'id' => $requirement->id,
            'title' => $requirement->title,
            'course_title' => $requirement->course_title,
            'status' => $requirement->status,
            'sections' => []
        ];

        foreach ($requirement->sections as $section) {
            $sectionData = [
                'section_letter' => $section->section_letter,
                'section_title' => $section->section_title,
                'sub_sections' => []
            ];

            foreach ($section->subSections as $subSection) {
                $subSectionData = [
                    'sub_section_title' => $subSection->sub_section_title,
                    'table_columns' => $subSection->table_columns ?
                        json_decode($subSection->table_columns, true) : [],
                    'items' => []
                ];

                foreach ($subSection->items as $item) {
                    $itemData = [
                        'item_number' => $item->item_number,
                        'item_title' => $item->item_title,
                        'description' => $item->description,
                        'table_values' => $item->table_values ?
                            json_decode($item->table_values, true) : [],
                        'is_checked' => (int)$item->is_checked,
                        'alternative_text' => $item->alternative_text
                    ];
                    $subSectionData['items'][] = $itemData;
                }

                $sectionData['sub_sections'][] = $subSectionData;
            }

            $formattedRequirement['sections'][] = $sectionData;
        }

        return response()->json($formattedRequirement);
    }

    public function update(Request $request, $id)
    {
        $requirement = LongitudinalRequirement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sections' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update the main requirement
            $requirement->update([
                'title' => $request->title,
                'course_title' => $request->course_title,
                'status' => $request->status,
            ]);

            // Delete all existing related data
            $requirement->sections()->each(function($section) {
                $section->subSections()->each(function($subSection) {
                    $subSection->items()->delete();
                });
                $section->subSections()->delete();
            });
            $requirement->sections()->delete();

            // Recreate sections and their data
            $this->processSections($requirement, $request->sections);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Longitudinal requirement form updated successfully!',
                'requirement' => $requirement->load(['sections.subSections.items'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update form: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $requirement = LongitudinalRequirement::findOrFail($id);

            // Delete all related data
            $requirement->sections()->each(function($section) {
                $section->subSections()->each(function($subSection) {
                    $subSection->items()->delete();
                });
                $section->subSections()->delete();
            });
            $requirement->sections()->delete();

            $requirement->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Longitudinal requirement form deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process sections, sub-sections, and items
     */
    private function processSections($requirement, $sections)
    {
        foreach ($sections as $sectionIndex => $sectionData) {
            $sectionLetter = isset($sectionData['section_letter']) ?
                             strtoupper($sectionData['section_letter']) : null;

            $section = LongitudinalSection::create([
                'longitudinal_requirement_id' => $requirement->id,
                'section_letter' => $sectionLetter,
                'section_title' => $sectionData['section_title'],
                'order' => $sectionIndex,
            ]);

            // Process sub-sections
            if (isset($sectionData['sub_sections']) && is_array($sectionData['sub_sections'])) {
                foreach ($sectionData['sub_sections'] as $subSectionIndex => $subSectionData) {
                    $subSection = LongitudinalSubSection::create([
                        'section_id' => $section->id,
                        'sub_section_title' => $subSectionData['sub_section_title'] ?? 'Untitled Section',
                        'sub_section_type' => $this->determineSubSectionType($sectionLetter),
                        'table_columns' => isset($subSectionData['table_columns']) ?
                            json_encode($subSectionData['table_columns']) : null,
                        'order' => $subSectionIndex,
                    ]);

                    // Process items
                    if (isset($subSectionData['items']) && is_array($subSectionData['items'])) {
                        foreach ($subSectionData['items'] as $itemIndex => $itemData) {
                            LongitudinalItem::create([
                                'sub_section_id' => $subSection->id,
                                'item_number' => $itemData['item_number'] ?? ($itemIndex + 1),
                                'item_title' => $itemData['item_title'] ?? 'Untitled Item',
                                'description' => $itemData['description'] ?? null,
                                'table_values' => isset($itemData['table_values']) ?
                                    json_encode($itemData['table_values']) : null,
                                'is_checked' => isset($itemData['is_checked']) ? (int)$itemData['is_checked'] : 0,
                                'alternative_text' => $itemData['alternative_text'] ?? null,
                                'order' => $itemIndex,
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Determine sub-section type based on section letter
     */
    private function determineSubSectionType($sectionLetter)
    {
        $types = [
            'A' => 'list',
            'B' => 'table',
            'C' => 'meeting',
            'D' => 'courses',
            'E' => 'research'
        ];

        return $types[strtoupper($sectionLetter)] ?? 'list';
    }
}
