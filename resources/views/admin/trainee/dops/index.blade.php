@extends('layouts.app')
@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            Trainee DOPS
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#dopsModal" onclick="openAdd()">
                <i class="fas fa-plus"></i>
            </button>
        </h3>

        <div>
            <a href="{{ route('trainee.dops.export.excel') }}" class="btn btn-success btn-sm">Excel</a>
            <a href="{{ route('trainee.dops.export.pdf') }}" class="btn btn-danger btn-sm">PDF</a>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#performanceModal">Performance</button>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- GRAND WARD ROUND TABLE --}}
    <div class="card">
        <div class="card-body">
            <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
                <thead class="table-dark">
<table class="table table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>By</th>
    <th>Date</th>
    <th>From</th>
    <th>To</th>
    <th>Involvement</th>
    <th>DOPS</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
@foreach($traineeDops as $k=>$d)
<tr>
    <td>{{ $k+1 }}</td>
    <td>{{ $d->trainee->name }}</td>
    <td>{{ $d->date }}</td>
    <td>{{ $d->from_time }}</td>
    <td>
    @if(!$d->to_time)
        <a href="{{ route('trainee.dops.end',$d) }}" onclick="return confirm('End activity?')" class="btn btn-danger btn-sm">End activity</a>
        @else
        {{ $d->to_time }}
        @endif
    </td>
<td>
                                <span class="badge bg-{{ $d->involvement=='A'?'success':'secondary' }}">
                                    {{ $d->involvement=='A'?'Active':'Waiting' }}
                                </span>
                                @if(auth()->user()->userType == 4)
                                <br>
                                <label class="switch">
                                    <input type="checkbox" {{ $d->involvement=='A'?'checked':'' }} onchange="toggleInvolvement({{ $r->id }})">
                                    <span class="slider round"></span>
                                </label>
                                @endif
                            </td>
    <td>{{ $d->dops->title }}</td>
    <td>
        <button class="btn btn-sm btn-warning" onclick="editDops({{ $d->id }})">Edit</button>
        <form method="POST" action="{{ route('trainee.dops.destroy',$d->id) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>

{{-- MODAL --}}
<div class="modal fade" id="dopsModal">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="POST" id="dopsForm">
@csrf
<div class="modal-header">
    <h5 id="modalTitle">Add DOPS</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<label>Rotation</label>
<select id="rotation_id" name="rotation_id" class="form-control mb-2">
<option value="">Select</option>
@foreach($rotations as $r)
<option value="{{ $r->id }}">{{ $r->title }}</option>
@endforeach
</select>

<label>DOPS</label>
<select id="dops_id" name="dops_id" class="form-control mb-2"></select>

<label>Date</label>
<input type="date" name="date" id="date" class="form-control mb-2">

<label>From Time</label>
<input type="time" name="from_time" id="from_time" class="form-control mb-2">

{{-- Diagnosis --}}
<label>Diagnosis</label>
<select id="diagnosis_select" class="form-control mb-2">
<option value="">Select</option>
<option value="1">Diagnosis 1</option>
<option value="2">Diagnosis 2</option>
<option value="3">Diagnosis 3</option>
</select>
<div id="diagnosis_inputs"></div>

{{-- Procedure --}}
<label>Procedure</label>
<select id="procedure_select" class="form-control mb-2">
<option value="">Select</option>
<option value="1">Procedure 1</option>
<option value="2">Procedure 2</option>
<option value="3">Procedure 3</option>
</select>
<div id="procedure_inputs"></div>

<textarea name="comments" class="form-control mt-2" placeholder="Comments"></textarea>

</div>
<div class="modal-footer">
<button class="btn btn-success">Save</button>
</div>
</form>
</div>
</div>
</div>

</div>
@endsection

@push('scripts')
<script>
function openAdd(){
    $('#modalTitle').text('Add DOPS');
    $('#dopsForm').attr('action','{{ route("trainee.dops.store") }}');
    $('#dopsForm input[name=_method]').remove();
    $('#diagnosis_inputs,#procedure_inputs').html('');
}

$('#rotation_id').change(function(){
    $.get('/trainee/get-dops/'+this.value,function(res){
        let o='<option value="">Select</option>';
        res.forEach(d=>o+=`<option value="${d.id}">${d.title}</option>`);
        $('#dops_id').html(o);
    });
});

function addInput(box,name,type){
    $('#'+box).append(`
    <div class="d-flex gap-1 mb-1">
        <input type="hidden" name="${type}_name[]" value="${name}">
        <input type="text" name="${type}_value[]" class="form-control" placeholder="${name}">
    </div>`);
}

$('#diagnosis_select').change(function(){
    let n=$(this).find(':selected').text();
    if(!this.value) return $('#diagnosis_inputs').html('');
    $('#diagnosis_inputs').html(`
    <div class="d-flex gap-1 mb-1">
        <input type="hidden" name="diagnosis_name[]" value="${n}">
        <input type="text" name="diagnosis_value[]" class="form-control" placeholder="${n}">
        <button type="button" class="btn btn-success" onclick="addInput('diagnosis_inputs','${n}','diagnosis')">+</button>
    </div>`);
});

$('#procedure_select').change(function(){
    let n=$(this).find(':selected').text();
    if(!this.value) return $('#procedure_inputs').html('');
    $('#procedure_inputs').html(`
    <div class="d-flex gap-1 mb-1">
        <input type="hidden" name="procedure_name[]" value="${n}">
        <input type="text" name="procedure_value[]" class="form-control" placeholder="${n}">
        <button type="button" class="btn btn-success" onclick="addInput('procedure_inputs','${n}','procedure')">+</button>
    </div>`);
});

function editDops(id){
    $.get('/trainee/dops/'+id+'/edit',function(d){
        openAdd();
        $('#modalTitle').text('Edit DOPS');
        $('#dopsForm').attr('action','/trainee/dops/'+id)
            .append('<input type="hidden" name="_method" value="PUT">');
        $('#rotation_id').val(d.rotation_id).trigger('change');
        setTimeout(()=>$('#dops_id').val(d.dops_id),400);
        $('#date').val(d.date);
        $('#from_time').val(d.from_time);
        $('#dopsModal').modal('show');
    });
}
</script>
@endpush
