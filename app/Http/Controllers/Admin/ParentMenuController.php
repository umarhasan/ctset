<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentMenu;
use Illuminate\Http\Request;

class ParentMenuController extends Controller
{
    public function index()
    {
        $records = ParentMenu::latest()->get();
        return view('admin.parent_menus.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        ParentMenu::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(ParentMenu::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        ParentMenu::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        ParentMenu::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
