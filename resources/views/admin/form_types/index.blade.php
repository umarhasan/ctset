@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Form Types</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
            <i class="fa fa-plus"></i> Add Form Type
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="formTypeTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                <tr>
                    <td>{{ $type->title }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $type->id }})"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $type->id }})"><i class="fa fa-trash"></i></button>
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
                        <label>Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
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
    $('#formTypeTable').DataTable();

    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Form Type');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function(id){
        $.get(`form-types/${id}/edit`, function(res){
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#modalTitle').text('Edit Form Type');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function(e){
        e.preventDefault();
        let id = $('#record_id').val();
        let url = id ? `form-types/${id}` : `form-types`;
        let formData = new FormData(this);
        if(id) formData.append('_method','PUT');

        $.ajax({ url:url, method:'POST', data:formData, processData:false, contentType:false, success:function(){ location.reload(); } });
    });

    window.deleteRecord = function(id){
        if(!confirm('Delete record?')) return;
        $.ajax({ url:`form-types/${id}`, method:'DELETE', data:{_token:'{{ csrf_token() }}'}, success:function(){ location.reload(); } });
    }
});
</script>
@endpush
