<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rotation;
use Illuminate\Http\Request;

class RotationController extends Controller
{
    public function index()
    {
        $rotations = Rotation::latest()->get();
        return view('admin.rotations.index', compact('rotations'));
    }
    private function makeShortName($title)
    {
        $words = preg_split('/\s+/', trim($title));
        $letters = [];
        $roman = [];

        foreach ($words as $word) {
            // Check for Roman numerals (I-X) or single-letter words
            if (preg_match('/^(I|II|III|IV|V|VI|VII|VIII|IX|X)$/i', $word)) {
                $roman[] = strtoupper($word);
            } else {
                $letters[] = strtoupper(substr($word, 0, 1));
            }
        }

        // Combine letters + Roman numerals
        return implode('', $letters) . (count($roman) ? ' ' . implode(' ', $roman) : '');
    }

    /**
     * Store a new rotation
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|string|max:255',
        ]);

        $data['short_name'] = $this->makeShortName($data['title']);

        Rotation::create($data);
        return response()->json(['success' => true]);
    }
    public function edit(Rotation $rotation)
    {
        return response()->json($rotation);
    }
    public function update(Request $r, Rotation $rotation)
    {
        $data = $r->validate([
            'title' => 'required|string|max:255',
        ]);
        $data['short_name'] = $this->makeShortName($data['title']);

        $rotation->update($data);
        return response()->json(['success' => true]);
    }
    public function destroy(Rotation $rotation)
    {
        $rotation->delete();
        return response()->json(['success' => true]);
    }
}
