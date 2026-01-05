<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Hospital::create($request->all());
        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully.');
    }

    public function edit(Hospital $hospital)
    {
         return response()->json($hospital);

    }

    public function update(Request $request, Hospital $hospital)
    {
            $request->validate(['name' => 'required|string|max:255']);
            $hospital->update($request->all());
            return response()->json(['success' => true]);
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return response()->json(['success' => true]);
    }
}
