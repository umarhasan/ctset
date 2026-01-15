@extends('layouts.app')
@section('content')

<div class="container">
<h4>QR Generate (CRUD)</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- GENERATE FORM -->
<form method="POST" action="{{ route('qr-generate.store') }}" class="card p-3 mb-4">
@csrf
<div class="row">
    <div class="col-md-5">
        <label>For</label>
        <select name="category_id" class="form-control" required>
            <option value="">-- Select --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100">Generate QR</button>
    </div>
</div>
</form>

<!-- LIST -->
<table class="table table-bordered">
<tr>
<th>#</th><th>Category</th><th>Date</th><th>QR</th><th>Action</th>
</tr>

@foreach($records as $r)
@php
$qrImg = \App\Http\Controllers\Admin\GeneratedQrController::makeQr($r->qr_data);
@endphp
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $r->category->name }}</td>
<td>{{ $r->date }}</td>
<td><img src="data:image/png;base64,{{ $qrImg }}" width="80"></td>
<td>
<button class="btn btn-sm btn-warning" data-bs-toggle="modal"
        data-bs-target="#edit{{ $r->id }}">Edit</button>

<button class="btn btn-sm btn-danger" data-bs-toggle="modal"
        data-bs-target="#del{{ $r->id }}">Delete</button>
</td>
</tr>

<!-- EDIT MODAL -->
<div class="modal fade" id="edit{{ $r->id }}">
<div class="modal-dialog">
<form method="POST" action="{{ route('qr-generate.update',$r->id) }}">
@csrf @method('PUT')
<div class="modal-content">
<div class="modal-header"><h5>Edit QR</h5></div>
<div class="modal-body">

<label>Category</label>
<select name="category_id" class="form-control">
@foreach($categories as $c)
<option value="{{ $c->id }}" {{ $r->category_id==$c->id?'selected':'' }}>
{{ $c->name }}
</option>
@endforeach
</select>

<label class="mt-2">Date</label>
<input type="date" name="date" value="{{ $r->date }}" class="form-control">

</div>
<div class="modal-footer">
<button class="btn btn-primary">Update</button>
</div>
</div>
</form>
</div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="del{{ $r->id }}">
<div class="modal-dialog">
<form method="POST" action="{{ route('qr-generate.destroy',$r->id) }}">
@csrf @method('DELETE')
<div class="modal-content">
<div class="modal-body">
Delete this QR record?
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-danger">Delete</button>
</div>
</div>
</form>
</div>
</div>

@endforeach
</table>

</div>
@endsection
