<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkCloud;

class WorkCloudController extends Controller
{
     // Display all work clouds
    public function index()
    {
        $workClouds = WorkCloud::all();
        return view('admin.work_clouds.index', compact('workClouds'));
    }

    // Show create form
    public function create()
    {
        return view('admin.work_clouds.create');
    }

    // Store new work cloud
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'min_score' => 'required|integer',
            'max_score' => 'required|integer',
            'weight' => 'required|integer',
        ]);

        WorkCloud::create($request->all());

        return redirect()->route('work_clouds.index')->with('success', 'Work Cloud created successfully.');
    }

    // Show edit form
    public function edit(WorkCloud $workCloud)
    {
        return response()->json($workCloud);

    }

    // Update work cloud
    public function update(Request $request, WorkCloud $workCloud)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'min_score' => 'required|integer',
            'max_score' => 'required|integer',
            'weight' => 'required|integer',
        ]);

        $workCloud->update($request->all());
        return response()->json(['success' => true]);

    }
    // Delete work cloud
    public function destroy(WorkCloud $workCloud)
    {
        $workCloud->delete();
        return response()->json(['success' => true]);

    }
}
