<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Competency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with('competency')->ordered()->get();
        $competencies = Competency::ordered()->get();
        return view('admin.ratings.index', compact('ratings', 'competencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'score' => 'required|integer|min:1|unique:ratings,score',
            'competency_id' => 'required|exists:competencies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Rating::create($validator->validated());
        return response()->json(['success' => true]);
    }

    public function edit(Rating $rating)
    {
        return response()->json($rating);
    }

    public function update(Request $request, Rating $rating)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'score' => 'required|integer|min:1|unique:ratings,score,' . $rating->id,
            'competency_id' => 'required|exists:competencies,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rating->update($validator->validated());
        return response()->json(['success' => true]);
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return response()->json(['success' => true]);
    }
}
