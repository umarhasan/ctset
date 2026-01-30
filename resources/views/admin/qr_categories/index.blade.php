@extends('layouts.app')
@section('content')

<div class="container">
    <h4>QR Category Master</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
        + Add Category
    </button>

    <!-- Table -->
    <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
         <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $cat)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cat->name }}</td>
                <td>
                    <span class="badge {{ $cat->status ? 'bg-success':'bg-danger' }}">
                        {{ $cat->status ? 'Active':'Inactive' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $cat->id }}">
                        Edit
                    </button>

                    <button class="btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $cat->id }}">
                        Delete
                    </button>
                </td>
            </tr>

            <!-- EDIT MODAL -->
            <div class="modal fade" id="editModal{{ $cat->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('qr-categories.update',$cat->id) }}">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>

                                <label class="mt-2">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $cat->status ? 'selected':'' }}>Active</option>
                                    <option value="0" {{ !$cat->status ? 'selected':'' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DELETE MODAL -->
            <div class="modal fade" id="deleteModal{{ $cat->id }}">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('qr-categories.destroy',$cat->id) }}">
                        @csrf @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Delete Category</h5>
                            </div>
                            <div class="modal-body">
                                Are you sure to delete <b>{{ $cat->name }}</b> ?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @endforeach
        </tbody>
    </table>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('qr-categories.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>

                    <label class="mt-2">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
