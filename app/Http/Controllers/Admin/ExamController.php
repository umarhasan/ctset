<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\TestType;
use App\Models\QuestionType;
use App\Models\MarketingType;
use App\Models\ExamDurationType;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $records = Exam::with([
            'testType',
            'questionTypes',
            'marketingType',
            'examDuration'
        ])->latest()->get();

        $testTypes = TestType::all();
        $questionTypes = QuestionType::all();
        $marketingTypes = MarketingType::all();
        $examDurations = ExamDurationType::all();

        return view(
            'admin.exams.index',
            compact('records', 'testTypes', 'questionTypes', 'marketingTypes', 'examDurations')
        );
    }

    public function edit(Exam $exam)
    {
        return response()->json(
            $exam->load('questionTypes')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_name' => 'required|string|max:255',
            'test_type' => 'required|exists:test_types,id',

            // ✅ MIX TYPE SUPPORT
            'question_types' => 'required|array|min:1',
            'question_types.*' => 'exists:question_types,id',

            'marketing' => 'nullable|exists:marketing_types,id',
            'exam_duration_type' => 'required|exists:exam_duration_types,id',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable',
            'hours' => 'nullable|integer',
            'minutes' => 'nullable|integer',
            'exam_requirement' => 'nullable|string',
            'exam_equipment' => 'nullable|string',
            'long_before' => 'nullable|string',
            'question_shuffling' => 'nullable|in:0,1',
            'previous_button' => 'nullable|in:0,1',
        ]);

        $exam = Exam::create(
            collect($data)->except('question_types')->toArray()
        );

        // ✅ SAVE MULTIPLE QUESTION TYPES
        $exam->questionTypes()->sync($data['question_types']);

        return response()->json(['message' => 'Exam created successfully']);
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'exam_name' => 'required|string|max:255',
            'test_type' => 'required|exists:test_types,id',

            // ✅ MIX TYPE SUPPORT
            'question_types' => 'required|array|min:1',
            'question_types.*' => 'exists:question_types,id',

            'marketing' => 'nullable|exists:marketing_types,id',
            'exam_duration_type' => 'required|exists:exam_duration_types,id',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable',
            'hours' => 'nullable|integer',
            'minutes' => 'nullable|integer',
            'exam_requirement' => 'nullable|string',
            'exam_equipment' => 'nullable|string',
            'long_before' => 'nullable|string',
            'question_shuffling' => 'nullable|in:0,1',
            'previous_button' => 'nullable|in:0,1',
        ]);

        $exam->update(
            collect($data)->except('question_types')->toArray()
        );

        $exam->questionTypes()->sync($data['question_types']);

        return response()->json(['message' => 'Exam updated successfully']);
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return response()->json(['message' => 'Exam deleted successfully']);
    }
}
