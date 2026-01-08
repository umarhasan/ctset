<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentFromType;
use App\Models\Rotation;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['fromType','rotation'])->latest()->get();
        $fromTypes   = AssignmentFromType::all();
        $rotations   = Rotation::all();

        // ✅ ONLY TRAINEE USERS (Spatie)
        $users = User::role('Trainee')->get();

        return view('admin.assignments.index', compact(
            'assignments','fromTypes','rotations','users'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_type_id' => 'required|exists:from_types,id',
            'from'         => 'required|string',
            'to'           => 'required|string',
            'users'        => 'required|array',
            'rotation_id'  => 'nullable|exists:rotations,id',
        ]);

        Assignment::create($data);

        return response()->json(['success' => true]);
    }

    public function edit(Assignment $assignment)
    {
        return response()->json($assignment);
    }

    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'from_type_id' => 'required|exists:from_types,id',
            'from'         => 'required|string',
            'to'           => 'required|string',
            'users'        => 'required|array',
            'rotation_id'  => 'nullable|exists:rotations,id',
        ]);

        $assignment->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return response()->json(['success' => true]);
    }
}
