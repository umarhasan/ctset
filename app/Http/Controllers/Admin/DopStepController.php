<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DopStep;
use Illuminate\Http\Request;

class DopStepController extends Controller
{
    public function index()
    {
        $records = DopStep::latest()->get();
        return view('admin.dop_steps.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        DopStep::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(DopStep::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        DopStep::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        DopStep::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
