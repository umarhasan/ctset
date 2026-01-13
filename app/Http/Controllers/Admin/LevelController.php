<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::get();
        return view('admin.levels.index', compact('levels'));
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'key' => 'required|unique:levels,key',
            'sequence' => 'required|integer|min:1',
            'description' => 'required',
            'score' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        Level::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Level saved successfully',
        ]);
    }

    public function edit(Level $level)
    {
        return response()->json($level);
    }

    public function update(Request $r, Level $level)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required',
            'key' => 'required|unique:levels,key,' . $level->id,
            'sequence' => 'required|integer|min:1',
            'description' => 'required',
            'score' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $level->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Level updated successfully',
        ]);
    }

    public function destroy(Level $level)
    {
        try {
            $level->delete();

            return response()->json([
                'success' => true,
                'message' => 'Level deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete this record',
            ], 500);
        }
    }
}
