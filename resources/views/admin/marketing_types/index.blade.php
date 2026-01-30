@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Marketing Types</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Marketing Type
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                <tr>
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
<div class="modal fade" id="masterModal">
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
                    <label>Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
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

    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#title').val('');
        $('#modalTitle').text('Add Marketing Type');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function (id) {
        $.get(`{{ route('marketing-types.index') }}/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#modalTitle').text('Edit Marketing Type');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function (e) {
        e.preventDefault();
        let id = $('#record_id').val();
        let url = id ? `{{ route('marketing-types.index') }}/${id}` : `{{ route('marketing-types.store') }}`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function () {
                $('#masterModal').modal('hide');
                location.reload();
            },
            error: function(xhr){
                alert(xhr.responseJSON.message || 'Something went wrong!');
            }
        });
    });

    window.deleteRecord = function (id) {
        if (!confirm('Delete record?')) return;

        $.ajax({
            url: `{{ route('marketing-types.index') }}/${id}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function () {
                location.reload();
            },
            error: function(xhr){
                alert(xhr.responseJSON.message || 'Could not delete!');
            }
        });
    }
});
</script>
@endpush
