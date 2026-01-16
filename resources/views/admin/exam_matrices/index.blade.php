@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Exam Matrices</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Exam Matrix
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead>
            <tr>
                <th>Grade</th>
                <th>Min Score</th>
                <th>Max Score</th>
                <th>Color</th>
                <th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($matrices as $matrix)
                <tr>
                    <td>{{ $matrix->grade }}</td>
                    <td>{{ $matrix->min_score }}</td>
                    <td>{{ $matrix->max_score }}</td>
                    <td>
                        <span style="background-color: {{ $matrix->color }}; padding: 5px 10px; color: #fff;">
                            {{ $matrix->color }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $matrix->id }})">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $matrix->id }})">
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
                        <label>Grade</label>
                        <input type="text" name="grade" id="grade" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Min Score</label>
                        <input type="number" name="min_score" id="min_score" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Max Score</label>
                        <input type="number" name="max_score" id="max_score" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Color</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                        <small class="text-muted">Hex code or color name</small>
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

    $('#examMatrixTable').DataTable();

    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#grade').val('');
        $('#min_score').val('');
        $('#max_score').val('');
        $('#color').val('');
        $('#modalTitle').text('Add Exam Matrix');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function (id) {
        $.get(`exam_matrices/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#grade').val(res.grade);
            $('#min_score').val(res.min_score);
            $('#max_score').val(res.max_score);
            $('#color').val(res.color);
            $('#modalTitle').text('Edit Exam Matrix');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `exam_matrices/${id}` : `/exam_matrices`;
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
            url: `exam_matrices/${id}`,
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
