@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Work Clouds</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Work Cloud
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Min Score</th>
                <th>Max Score</th>
                <th>Weight</th>
                <th width="120">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($workClouds as $cloud)
                <tr>
                    <td>{{ $cloud->title }}</td>
                    <td>{{ $cloud->category }}</td>
                    <td>{{ $cloud->min_score }}</td>
                    <td>{{ $cloud->max_score }}</td>
                    <td>{{ $cloud->weight }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $cloud->id }})">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $cloud->id }})">
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
                        <label>Category</label>
                        <input type="text" name="category" id="category" class="form-control" required>
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
                        <label>Weight</label>
                        <input type="number" name="weight" id="weight" class="form-control" required>
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

    $('#workCloudTable').DataTable();

    window.openCreateModal = function () {
        $('#record_id').val('');
        $('#title').val('');
        $('#category').val('');
        $('#min_score').val('');
        $('#max_score').val('');
        $('#weight').val('');
        $('#modalTitle').text('Add Work Cloud');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function (id) {
        $.get(`work_clouds/${id}/edit`, function (res) {
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#category').val(res.category);
            $('#min_score').val(res.min_score);
            $('#max_score').val(res.max_score);
            $('#weight').val(res.weight);
            $('#modalTitle').text('Edit Work Cloud');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `work_clouds/${id}` : `/work_clouds`;
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
            url: `work_clouds/${id}`,
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
