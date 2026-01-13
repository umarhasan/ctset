<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::get();
        return view('admin.ratings.index', compact('ratings'));
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'title' => 'required',
            'score' => 'required|integer|min:1|unique:ratings,score',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        Rating::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Rating saved successfully',
        ]);
    }

    public function edit(Rating $rating)
    {
        return response()->json($rating);
    }

    public function update(Request $r, Rating $rating)
    {
        $validator = Validator::make($r->all(), [
            'title' => 'required',
            'score' => 'required|integer|min:1|unique:ratings,score,' . $rating->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $rating->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully',
        ]);
    }

    public function destroy(Rating $rating)
    {
        try {
            $rating->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rating deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete this record',
            ], 500);
        }
    }
}
