@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Assignments</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
            <i class="fa fa-plus"></i> Add Assignment
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped" id="assignmentTable">
            <thead>
                <tr>
                    <th>Form Type</th>
                    <th>Rotation</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Users (Trainee)</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assign)
                <tr>
                    <td>{{ $assign->fromType->title ?? '-' }}</td>
                    <td>{{ $assign->rotation->title ?? '-' }}</td>
                    <td>{{ $assign->from }}</td>
                    <td>{{ $assign->to }}</td>
                    <td>
                        @php
                            $names = \App\Models\User::whereIn(
                                'id',
                                $assign->users ?? []
                            )->pluck('name')->toArray();
                        @endphp
                        {{ implode(', ', $names) }}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm"
                            onclick="openEditModal({{ $assign->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm"
                            onclick="deleteRecord({{ $assign->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="masterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle"></h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Form Type</label>
                            <select name="from_type_id" id="from_type_id"
                                class="form-control" required>
                                <option value="">Select</option>
                                @foreach($fromTypes as $ft)
                                    <option value="{{ $ft->id }}">
                                        {{ $ft->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Rotation</label>
                            <select name="rotation_id" id="rotation_id"
                                class="form-control">
                                <option value="">Select</option>
                                @foreach($rotations as $rot)
                                    <option value="{{ $rot->id }}">
                                        {{ $rot->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>From</label>
                            <input type="text" name="from" id="from"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>To</label>
                            <input type="text" name="to" id="to"
                                class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Trainee Users</label>
                            <select name="users[]" id="users"
                                class="form-control" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
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
$(function(){

    $('#assignmentTable').DataTable();

    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#users').val([]).trigger('change');
        $('#modalTitle').text('Add Assignment');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal = function(id){
        $.get(`assignments/${id}/edit`, function(res){
            $('#record_id').val(res.id);
            $('#from_type_id').val(res.from_type_id);
            $('#rotation_id').val(res.rotation_id);
            $('#from').val(res.from);
            $('#to').val(res.to);
            $('#users').val(res.users).trigger('change');
            $('#modalTitle').text('Edit Assignment');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function(e){
        e.preventDefault();
        let id = $('#record_id').val();
        let url = id ? `assignments/${id}` : `assignments`;

        let formData = new FormData(this);
        if(id) formData.append('_method','PUT');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(){
                location.reload();
            }
        });
    });

    window.deleteRecord = function(id){
        if(!confirm('Delete record?')) return;
        $.ajax({
            url: `assignments/${id}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(){
                location.reload();
            }
        });
    }

});
</script>
@endpush
