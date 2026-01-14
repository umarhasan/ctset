<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCategory;
use Illuminate\Http\Request;

class QrCategoryController extends Controller
{
   public function index()
    {
        $categories = QrCategory::latest()->get();
        return view('admin.qr_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required']);
        QrCategory::create($request->only('name','status'));
        return back()->with('success','Category added');
    }

    public function update(Request $request, QrCategory $qr_category)
    {
        $request->validate(['name'=>'required']);
        $qr_category->update($request->only('name','status'));
        return back()->with('success','Category updated');
    }

    public function destroy(QrCategory $qr_category)
    {
        $qr_category->delete();
        return back()->with('success','Category deleted');
    }
}
