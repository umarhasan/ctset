<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function index()
    {
        $records = TestType::latest()->get();
        return view('admin.test_types.index', compact('records'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        TestType::create($request->only('title'));

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $record = TestType::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $record = TestType::findOrFail($id);
        $record->update($request->only('title'));

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        $record = TestType::findOrFail($id);
        $record->delete();

        return response()->json(['success' => true]);
    }
}
