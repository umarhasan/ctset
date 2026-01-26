<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    public function index()
    {
        $procedures = Procedure::latest()->get();
        return view('admin.procedures.index', compact('procedures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Procedure::create($data);

        return response()->json(['success' => true]);
    }

    public function edit(Procedure $procedure)
    {
        return response()->json($procedure);
    }

    public function update(Request $request, Procedure $procedure)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $procedure->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Procedure $procedure)
    {
        $procedure->delete();
        return response()->json(['success' => true]);
    }
}
