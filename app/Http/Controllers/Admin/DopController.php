<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dop;
use Illuminate\Http\Request;

class DopController extends Controller
{
    public function index()
    {
        $records = Dop::latest()->get();
        return view('admin.dops.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        Dop::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(Dop::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        Dop::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        Dop::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
