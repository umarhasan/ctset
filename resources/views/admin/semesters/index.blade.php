@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Semesters</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Semester
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($semesters as $semester)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $semester->title }}</td>
                    <td>{{ $semester->start }}</td>
                    <td>{{ $semester->end }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $semester->id }})">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $semester->id }})">
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
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Start Date</label>
                        <input type="date" name="start" id="start" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>End Date</label>
                        <input type="date" name="end" id="end" class="form-control" required>
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

    $('#semesterTable').DataTable();

    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#title').val('');
        $('#start').val('');
        $('#end').val('');
        $('#modalTitle').text('Add Semester');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function (id) {
        $.get(`semesters/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#start').val(res.start);
            $('#end').val(res.end);
            $('#modalTitle').text('Edit Semester');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `semesters/${id}` : `/semesters`;
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
            url: `semesters/${id}`,
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
