@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Timetable Events</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Timetable Events
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Category</th>
                    <th>Reminder Days</th>
            <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                 @foreach($events as $event)
            <tr>
                <td class="text-center">{{ $event->id }}</td>
                <td class="text-center">{{ $event->title }}</td>
                <td class="text-center">
                    @if($event->image)
                        <img src="{{ asset('storage/'.$event->image) }}"
                            width="50" height="50"
                            class="rounded"
                            alt="event image">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td class="text-center">{{ $event->date }}</td>
                <td>
                    {{ $event->from_time }} - {{ $event->to_time }}
                </td class="text-center">
                <td class="text-center">{{ $event->category->title ?? '-' }}</td>
                <td class="text-center">
                    {{ $event->reminder_days ?? 'N/A' }}
                </td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm"
                        onclick="openEditModal({{ $event->id }})">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button class="btn btn-danger btn-sm"
                        onclick="deleteRecord({{ $event->id }})">
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
    <div class="modal-dialog modal-lg">
        <form id="masterForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>From Time</label>
                            <input type="time" name="from_time" id="from_time" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>To Time</label>
                            <input type="time" name="to_time" id="to_time" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label><input type="checkbox" name="is_superviser" id="is_superviser"> Supervisor</label>
                            <label class="ms-3"><input type="checkbox" name="is_trainee" id="is_trainee"> Trainee</label>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Reminder Days</label>
                            <input type="number" name="reminder_days" id="reminder_days" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

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

    $('#eventTable').DataTable();

    // CREATE
    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('Add Event');
        new bootstrap.Modal('#masterModal').show();
    }

    // EDIT
    window.openEditModal = function(id){
        $.get(`timetable-events/${id}/edit`, function(res){
            $('#record_id').val(res.id);
            $('#title').val(res.title);
            $('#category_id').val(res.category_id);
            $('#date').val(res.date);
            $('#from_time').val(res.from_time);
            $('#to_time').val(res.to_time);
            $('#reminder_days').val(res.reminder_days);
            $('#description').val(res.description);
            $('#is_superviser').prop('checked',res.is_superviser);
            $('#is_trainee').prop('checked',res.is_trainee);

            $('#modalTitle').text('Edit Event');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    // SAVE / UPDATE
    $('#masterForm').submit(function(e){
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `timetable-events/${id}` : `timetable-events`;

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
            url:`timetable-events/${id}`,
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
