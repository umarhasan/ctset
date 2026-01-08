<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ads', 'public');
        }

        Ad::create($data);

        return response()->json(['success' => true]);
    }

    public function edit(Ad $ad)
    {
        return response()->json($ad);
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ads', 'public');
        }

        $ad->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();
        return response()->json(['success' => true]);
    }
}
