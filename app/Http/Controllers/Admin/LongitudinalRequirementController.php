<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LongitudinalRequirement;
use Illuminate\Http\Request;

class LongitudinalRequirementController extends Controller
{
    public function index()
    {
        $records = LongitudinalRequirement::latest()->get();
        return view('admin.longitudinal_requirements.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        LongitudinalRequirement::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(LongitudinalRequirement::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        LongitudinalRequirement::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        LongitudinalRequirement::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
