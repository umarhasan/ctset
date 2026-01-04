<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuPage;
use Illuminate\Http\Request;

class MenuPageController extends Controller
{
    public function index()
    {
        $records = MenuPage::latest()->get();
        return view('admin.menu_pages.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        MenuPage::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(MenuPage::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        MenuPage::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        MenuPage::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
