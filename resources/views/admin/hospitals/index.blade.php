@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Hospitals</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Hospital
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($hospitals as $hospital)
                <tr>
                    <td>{{ $hospital->name }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $hospital->id }})">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $hospital->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
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
                    <div class="mb-3">
                        <label>Hospital Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
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

    $('#hospitalTable').DataTable();

    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#name').val('');
        $('#modalTitle').text('Add Hospital');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function (id) {
        $.get(`hospitals/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#name').val(res.name);
            $('#modalTitle').text('Edit Hospital');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `hospitals/${id}` : `/hospitals`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function () {
                location.reload();
            }
        });
    });

    window.deleteRecord = function (id) {
        if (!confirm('Delete record?')) return;

        $.ajax({
            url: `hospitals/${id}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function () {
                location.reload();
            }
        });
    }
});
</script>
@endpush
