<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FromType;
use Illuminate\Http\Request;

class FormTypeController extends Controller
{
    public function index()
    {
        $types = FromType::latest()->get();
        return view('admin.form_types.index', compact('types'));
    }
    public function store(Request $r)
    {
        $data = $r->validate(['title' => 'required']);
        FromType::create($data);
        return response()->json(['success' => true]);
    }
    public function edit(FromType $type)
    {
        return response()->json($type);
    }
    public function update(Request $r, FromType $type)
    {
        $data = $r->validate(['title' => 'required']);
        $type->update($data);
        return response()->json(['success' => true]);
    }
    public function destroy(FromType $type)
    {
        $type->delete();
        return response()->json(['success' => true]);
    }
}
