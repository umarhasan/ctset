<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModalType;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModalTypeController extends Controller
{
    public function index()
    {
        $modalTypes = ModalType::with('department')->latest()->get();
        $departments = Department::active()->get();
        $tables = ModalType::getAvailableTables();
        return view('admin.modal-types.index', compact('modalTypes','departments','tables'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'short_code'=>'nullable|string|max:10|unique:modal_types,short_code,NULL,id,table_name,'.$request->table_name,
            'department_id'=>'required|exists:departments,id',
            'table_name'=>'required|string',
            'description'=>'nullable|string'
        ]);

        if(empty($data['short_code'])){
            $data['short_code'] = strtoupper(Str::limit(preg_replace('/\s+/', '', $data['name']),10,''));
            $count = ModalType::where('short_code',$data['short_code'])->where('table_name',$data['table_name'])->count();
            if($count>0) $data['short_code'] .= $count+1;
        }

        $data['is_active'] = $request->has('is_active');
        ModalType::create($data);

        return redirect()->back()->with('success','Modal Type Added Successfully');
    }

    public function update(Request $request, $id)
    {
        $modalType = ModalType::findOrFail($id);

        $data = $request->validate([
            'name'=>'required|string|max:255',
            'short_code'=>'nullable|string|max:10|unique:modal_types,short_code,'.$id.',id,table_name,'.$request->table_name,
            'department_id'=>'required|exists:departments,id',
            'table_name'=>'required|string',
            'description'=>'nullable|string'
        ]);

        if(empty($data['short_code'])){
            $data['short_code'] = strtoupper(Str::limit(preg_replace('/\s+/', '', $data['name']),10,''));
            $count = ModalType::where('short_code',$data['short_code'])->where('table_name',$data['table_name'])->where('id','!=',$id)->count();
            if($count>0) $data['short_code'] .= $count+1;
        }

        $data['is_active'] = $request->has('is_active');
        $modalType->update($data);

        return redirect()->back()->with('success','Modal Type Updated Successfully');
    }

    public function destroy($id)
    {
        ModalType::findOrFail($id)->delete();
        return redirect()->back()->with('success','Modal Type Deleted Successfully');
    }
}