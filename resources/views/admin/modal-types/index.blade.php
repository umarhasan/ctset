@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Modal Types</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModalTypeModal">Add Modal Type</button>
    </div>

    <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Short Code</th>
                <th>Department</th>
                <th>Table</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modalTypes as $modal)
            <tr>
                <td>{{ $modal->id }}</td>
                <td>{{ $modal->name }}</td>
                <td>{{ $modal->short_code }}</td>
                <td>{{ $modal->department->name }}</td>
                <td>{{ $modal->table_label }}</td>
                <td>{{ $modal->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModalTypeModal{{ $modal->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModalTypeModal{{ $modal->id }}">Delete</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModalTypeModal{{ $modal->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('modal-types.update', $modal->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Edit Modal Type</h5></div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" value="{{ $modal->name }}" placeholder="Name" required>
                                <input type="text" name="short_code" class="form-control mb-2" value="{{ $modal->short_code }}" placeholder="Short Code (auto if empty)">
                                <select name="department_id" class="form-control mb-2" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $modal->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <select name="table_name" class="form-control mb-2" required>
                                    <option value="">Select Table</option>
                                    @foreach($tables as $key => $label)
                                        <option value="{{ $key }}" {{ $modal->table_name == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <textarea name="description" class="form-control mb-2" placeholder="Description">{{ $modal->description }}</textarea>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_active" class="form-check-input" {{ $modal->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="modal-footer"><button class="btn btn-success">Update</button></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModalTypeModal{{ $modal->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('modal-types.destroy', $modal->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Delete Modal Type</h5></div>
                            <div class="modal-body">Are you sure you want to delete <strong>{{ $modal->name }}</strong>?</div>
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
<div class="modal fade" id="addModalTypeModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('modal-types.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5>Add Modal Type</h5></div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                    <input type="text" name="short_code" class="form-control mb-2" placeholder="Short Code (auto if empty)">
                    <select name="department_id" class="form-control mb-2" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <select name="table_name" class="form-control mb-2" required>
                        <option value="">Select Table</option>
                        @foreach($tables as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
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