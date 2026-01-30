@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Diagnoses</h4>
        <button class="btn btn-primary" id="addBtn">Add Diagnosis</button>
    </div>

    <table class="table table-bordered">
         <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th width="150">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($diagnoses as $diagnosis)
            <tr id="row-{{ $diagnosis->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $diagnosis->title }}</td>
                <td>{{ $diagnosis->description }}</td>
                <td>
                    <button class="btn btn-sm btn-info editBtn"
                        data-id="{{ $diagnosis->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn"
                        data-id="{{ $diagnosis->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- MODAL --}}
<div class="modal fade" id="mainModal">
    <div class="modal-dialog">
        <form id="mainForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea id="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {

    $('#addBtn').click(function () {
        $('#mainForm')[0].reset();
        $('#record_id').val('');
        $('#mainModal').modal('show');
    });

    $('#mainForm').submit(function (e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `/diagnoses/${id}` : `/diagnoses`;
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: {
                _token: '{{ csrf_token() }}',
                title: $('#title').val(),
                description: $('#description').val()
            },
            success: () => location.reload()
        });
    });

    $('.editBtn').click(function () {
        let id = $(this).data('id');

        $.get(`/diagnoses/${id}/edit`, data => {
            $('#record_id').val(data.id);
            $('#title').val(data.title);
            $('#description').val(data.description);
            $('#mainModal').modal('show');
        });
    });

    $('.deleteBtn').click(function () {
        if (!confirm('Delete this diagnosis?')) return;

        let id = $(this).data('id');

        $.ajax({
            url: `/diagnoses/${id}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: () => $('#row-' + id).remove()
        });
    });

});
</script>
@endpush
