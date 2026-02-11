@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Departments</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">Add Department</button>
    </div>

    <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Short Code</th>
                <th>Module</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
            <tr>
                <td>{{ $department->id }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->short_code }}</td>
                <td>{{ $department->module_label }}</td>
                <td>{{ $department->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal{{ $department->id }}">Delete</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editDepartmentModal{{ $department->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('departments.update',$department->id) }}">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Edit Department</h5></div>
                            <div class="modal-body">
                                <input type="text" name="name" value="{{ $department->name }}" class="form-control mb-2" placeholder="Department Name" required>
                                <input type="text" name="short_code" value="{{ $department->short_code }}" class="form-control mb-2" placeholder="Short Code (auto if empty)">
                                <select name="module_type" class="form-control mb-2">
                                    <option value="C" {{ $department->module_type=='C'?'selected':'' }}>Clinical</option>
                                    <option value="S" {{ $department->module_type=='S'?'selected':'' }}>Scientific</option>
                                    <option value="M" {{ $department->module_type=='M'?'selected':'' }}>MATCVS</option>
                                    <option value="D" {{ $department->module_type=='D'?'selected':'' }}>Department</option>
                                    <option value="E" {{ $department->module_type=='E'?'selected':'' }}>Evaluation</option>
                                </select>
                                <textarea name="description" class="form-control" placeholder="Description">{{ $department->description }}</textarea>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_active" class="form-check-input" {{ $department->is_active?'checked':'' }}>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="modal-footer"><button class="btn btn-success">Update</button></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteDepartmentModal{{ $department->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('departments.destroy',$department->id) }}">
                        @csrf @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Delete Department</h5></div>
                            <div class="modal-body">Are you sure?</div>
                            <div class="modal-footer"><button class="btn btn-danger">Delete</button></div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDepartmentModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5>Add Department</h5></div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Department Name" required>
                    <input type="text" name="short_code" class="form-control mb-2" placeholder="Short Code (auto if empty)">
                    <select name="module_type" class="form-control mb-2">
                        <option value="C">Clinical</option>
                        <option value="S">Scientific</option>
                        <option value="M">MATCVS</option>
                        <option value="D">Department</option>
                        <option value="E">Evaluation</option>
                    </select>
                    <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" class="form-check-input" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">Save</button></div>
            </div>
        </form>
    </div>
</div>
@endsection