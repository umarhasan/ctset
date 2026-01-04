<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingType;
use Illuminate\Http\Request;

class MarketingTypeController extends Controller
{
    public function index()
    {
        $records = MarketingType::latest()->get();
        return view('admin.marketing_types.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);
        MarketingType::create($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function edit($id)
    {
        return response()->json(MarketingType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title'=>'required']);
        MarketingType::findOrFail($id)->update($request->only('title'));
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        MarketingType::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
