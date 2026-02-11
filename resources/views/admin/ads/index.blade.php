@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Ads</h3>

        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Ads
            </button>
        </div>

    </div>

    <div class="card-body">
            <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
             <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ads as $ad)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ad->title }}</td>
                    <td>
                        @if($ad->image)
                        <img src="{{ asset('storage/'.$ad->image) }}" width="60" alt="Ad Image">
                        @endif
                    </td>
                    <td>{{ $ad->description }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $ad->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $ad->id }})">
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
        <form id="masterForm" enctype="multipart/form-data">
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

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" id="image" class="form-control">
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
    $('#adsTable').DataTable();
    // CREATE
    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Ad');
        new bootstrap.Modal('#masterModal').show();
    }

    // EDIT
    window.openEditModal = function(id){
        $.get(`ads/${id}/edit`, function(res){
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#description').val(res.description);
            $('#modalTitle').text('Edit Ad');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    // SAVE / UPDATE
    $('#masterForm').submit(function(e){
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `ads/${id}` : `ads`;

        let formData = new FormData(this);
        if(id) formData.append('_method','PUT');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData:false,
            contentType:false,
            success:function(){
                location.reload();
            }
        });
    });

    // DELETE
    window.deleteRecord = function(id){
        if(!confirm('Delete record?')) return;

        $.ajax({
            url:`ads/${id}`,
            method:'DELETE',
            data:{_token:'{{ csrf_token() }}'},
            success:function(){
                location.reload();
            }
        });
    }
});
</script>
@endpush
