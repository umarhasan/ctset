@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">Time Table Categories</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Category
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead class="table-light">
                <tr>
                    <th>Category Name</th>
                    <th>Title</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                <tr>
                    <td>{{ $row->category }}</td>
                    <td>{{ $row->title }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $row->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $row->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="masterModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="category" id="category" class="form-control" placeholder="Category Name" required>
                        <label for="category">Category Name</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
                        <label for="title">Title</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#masterTable').DataTable();

    let modalEl = document.getElementById('masterModal');
    let modal = new bootstrap.Modal(modalEl);

    // Open Create Modal
    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Time Table Category');
        modal.show();
    }

    // Open Edit Modal
    window.openEditModal = function (id) {
        $.get(`{{ url('time-table-categories') }}/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#category').val(res.category);
            $('#title').val(res.title);
            $('#modalTitle').text('Edit Time Table Category');
            modal.show();
        });
    }

    // Store / Update
    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `{{ url('time-table-categories') }}/${id}` : `{{ route('time-table-categories.store') }}`;
        let formData = $(this).serialize();
        if (id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function () {
                location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });

    // Delete
    window.deleteRecord = function (id) {
        if(!confirm('Are you sure you want to delete this category?')) return;

        $.ajax({
            url: `{{ url('time-table-categories') }}/${id}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function () {
                location.reload();
            }
        });
    }
});
</script>
@endpush
