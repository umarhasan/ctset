<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamMatrix;
class ExamMatrixController extends Controller
{
    // Display all exam matrices
    public function index()
    {
        $matrices = ExamMatrix::all();
        return view('admin.exam_matrices.index', compact('matrices'));
    }

    // Show create form
    public function create()
    {
        return view('admin.exam_matrices.create');
    }

    // Store new exam matrix
    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required|string|max:10',
            'min_score' => 'required|integer',
            'max_score' => 'required|integer',
            'color' => 'required|string|max:20',
        ]);

        ExamMatrix::create($request->all());

        return redirect()->route('exam_matrices.index')->with('success', 'Exam matrix created successfully.');
    }

    // Show edit form
    public function edit(ExamMatrix $examMatrix)
    {
         return response()->json($examMatrix);

    }

    // Update exam matrix
    public function update(Request $request, ExamMatrix $examMatrix)
    {
            $request->validate([
                'grade' => 'required|string|max:10',
                'min_score' => 'required|integer',
                'max_score' => 'required|integer',
                'color' => 'required|string|max:20',
            ]);

            $examMatrix->update($request->all());
            return response()->json(['success' => true]);
        }

    // Delete exam matrix
    public function destroy(ExamMatrix $examMatrix)
    {
        $examMatrix->delete();
        return response()->json(['success' => true]);
    }
}
