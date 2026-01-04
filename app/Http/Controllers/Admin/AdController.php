<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $records = Ad::latest()->get();
        return view('admin.ads.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        Ad::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(Ad::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        Ad::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        Ad::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
