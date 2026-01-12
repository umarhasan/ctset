<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Competency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::with('competency')->ordered()->get();
        $competencies = Competency::ordered()->get();
        return view('admin.levels.index', compact('levels', 'competencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:levels,key',
            'sequence' => 'required|integer|min:1',
            'description' => 'required|string',
            'score' => 'required|integer|min:0',
            'competency_id' => 'required|exists:competencies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Level::create($validator->validated());
        return response()->json(['success' => true]);
    }

    public function edit(Level $level)
    {
        return response()->json($level);
    }

    public function update(Request $request, Level $level)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:levels,key,' . $level->id,
            'sequence' => 'required|integer|min:1',
            'description' => 'required|string',
            'score' => 'required|integer|min:0',
            'competency_id' => 'required|exists:competencies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $level->update($validator->validated());
        return response()->json(['success' => true]);
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return response()->json(['success' => true]);
    }
}
