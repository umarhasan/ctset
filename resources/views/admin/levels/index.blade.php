@extends('layouts.app')

@section('title', 'Levels Management')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Levels</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="bi bi-plus-circle"></i> Add Level
            </button>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="levelsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Key</th>
                    <th>Sequence</th>
                    <th>Score</th>
                    <th>Description</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($levels as $level)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $level->name }}</td>
                    <td><span class="badge bg-secondary">{{ $level->key }}</span></td>
                    <td><span class="badge bg-info">{{ $level->sequence }}</span></td>
                    <td><span class="badge bg-success">{{ $level->score }}</span></td>
                    <td>{{ Str::limit($level->description, 50) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $level->id }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $level->id }})">
                            <i class="bi bi-trash"></i>
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
                    <h5 id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Key *</label>
                        <input type="text" name="key" id="key" class="form-control" required>
                        <small class="text-muted">Unique identifier</small>
                    </div>

                    <div class="mb-3">
                        <label>Sequence *</label>
                        <input type="number" name="sequence" id="sequence" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label>Score *</label>
                        <input type="number" name="score" id="score" class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label>Description *</label>
                        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#levelsTable').DataTable();

    // CREATE
    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Level');
        new bootstrap.Modal('#masterModal').show();
    }

    // EDIT
    window.openEditModal = function(id){
        $.get('levels/' + id + '/edit', function(res){
            $('#record_id').val(res.id);
            $('#name').val(res.name);
            $('#key').val(res.key);
            $('#sequence').val(res.sequence);
            $('#score').val(res.score);
            $('#description').val(res.description);
            $('#modalTitle').text('Edit Level');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    // SAVE/UPDATE
    $('#masterForm').submit(function(e){
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? 'levels/' + id : 'levels';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize() + (id ? '&_method=PUT' : ''),
            success: function(){
                Swal.fire('Success!', 'Record saved successfully.', 'success');
                $('#masterModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr){
                let errors = xhr.responseJSON.errors;
                let errorMsg = '';
                $.each(errors, function(key, value){
                    errorMsg += value[0] + '\n';
                });
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });

    // DELETE
    window.deleteRecord = function(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'levels/' + id,
                    method: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(){
                        Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            }
        });
    }
});
</script>
@endpush
