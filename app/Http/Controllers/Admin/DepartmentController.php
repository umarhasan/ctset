<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('modalTypes')->latest()->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:10|unique:departments,short_code',
            'module_type' => 'required|in:C,S,M,D,E',
            'description' => 'nullable|string'
        ]);

        if(empty($data['short_code'])){
            $data['short_code'] = strtoupper(Str::limit(preg_replace('/\s+/', '', $data['name']),10,''));
            $count = Department::where('short_code',$data['short_code'])->count();
            if($count>0) $data['short_code'] .= $count+1;
        }

        $data['is_active'] = $request->has('is_active');
        Department::create($data);

        return redirect()->back()->with('success','Department Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:10|unique:departments,short_code,'.$id,
            'module_type' => 'required|in:C,S,M,D,E',
            'description' => 'nullable|string'
        ]);

        if(empty($data['short_code'])){
            $data['short_code'] = strtoupper(Str::limit(preg_replace('/\s+/', '', $data['name']),10,''));
            $count = Department::where('short_code',$data['short_code'])->where('id','!=',$id)->count();
            if($count>0) $data['short_code'] .= $count+1;
        }

        $data['is_active'] = $request->has('is_active');
        $department->update($data);

        return redirect()->back()->with('success','Department Updated Successfully');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        if($department->modalTypes()->count()>0){
            return redirect()->back()->with('error','Cannot delete department with modal types.');
        }
        $department->delete();
        return redirect()->back()->with('success','Department Deleted Successfully');
    }
}