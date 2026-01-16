@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Video Categories</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Video Categories
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead>
                <tr>
                    <th>Category</th>
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
                        <button class="btn btn-warning btn-sm"
                                onclick="openEditModal({{ $row->id }})">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm"
                                onclick="deleteRecord({{ $row->id }})">
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

                    <div class="form-group mb-2">
                        <label>Category</label>
                        <input type="text" name="category" id="category"
                               class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="title"
                               class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
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

    let modal = new bootstrap.Modal(document.getElementById('masterModal'));

    // Create
    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#category').val('');
        $('#title').val('');
        $('#modalTitle').text('Add Video Category');
        modal.show();
    }

    // Edit
    window.openEditModal = function (id) {
        $.get(`{{ url('video-categories') }}/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#category').val(res.category);
            $('#title').val(res.title);
            $('#modalTitle').text('Edit Video Category');
            modal.show();
        });
    }

    // Store / Update
    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id
            ? `{{ url('video-categories') }}/${id}`
            : `{{ route('video-categories.store') }}`;

        let formData = $(this).serialize();

        if (id) {
            formData += '&_method=PUT';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function () {
                location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message ?? 'Something went wrong');
            }
        });
    });

    // Delete
    window.deleteRecord = function (id) {
        if (!confirm('Delete record?')) return;

        $.ajax({
            url: `{{ url('video-categories') }}/${id}`,
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
