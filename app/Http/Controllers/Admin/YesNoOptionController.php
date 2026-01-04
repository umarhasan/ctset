<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YesNoOption;
use Illuminate\Http\Request;

class YesNoOptionController extends Controller
{
    public function index()
    {
        $records = YesNoOption::latest()->get();
        return view('admin.yes_no_options.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        YesNoOption::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(YesNoOption::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        YesNoOption::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        YesNoOption::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
