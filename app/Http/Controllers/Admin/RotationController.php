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
    public function store(Request $r)
    {
        $data = $r->validate(['title' => 'required']);
        Rotation::create($data);
        return response()->json(['success' => true]);
    }
    public function edit(Rotation $rotation)
    {
        return response()->json($rotation);
    }
    public function update(Request $r, Rotation $rotation)
    {
        $data = $r->validate(['title' => 'required']);
        $rotation->update($data);
        return response()->json(['success' => true]);
    }
    public function destroy(Rotation $rotation)
    {
        $rotation->delete();
        return response()->json(['success' => true]);
    }
}
