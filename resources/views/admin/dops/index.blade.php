@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>DOPS Management</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dopsModal">
            <i class="fa fa-plus"></i> Add DOPS
        </button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            @foreach($dops as $k=>$d)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ $d->title }}</td>
                <td>{{ $d->status ? 'Active':'Inactive' }}</td>
                <td>
                    <form method="POST" action="{{ route('dops.destroy',$d->id) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="dopsModal">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<form method="POST" action="{{ route('dops.store') }}" onsubmit="prepareHiddenHtml()">
@csrf

<input type="hidden" name="level_html" id="level_html">
<input type="hidden" name="rating_html" id="rating_html">
<input type="hidden" name="competency_html" id="competency_html">

<div class="modal-content">
<div class="modal-header bg-primary text-white">
<h5>Add DOPS</h5>
</div>

<div class="modal-body">

<input name="title" class="form-control mb-2" placeholder="Title">

<textarea name="steps" id="steps"></textarea>

<label>Rotation</label>
<select name="rotation_ids[]" multiple class="form-control mb-2">
@foreach($rotations as $r)
<option value="{{ $r->id }}">{{ $r->title }}</option>
@endforeach
</select>

<label>Level</label>
<select name="level_id[]" id="level_select" multiple class="form-control mb-2">
@foreach($levels as $l)
<option value="{{ $l->id }}">{{ $l->name }}</option>
@endforeach
</select>

<label>Rating</label>
<select name="rating_id[]" id="rating_select" multiple class="form-control mb-2">
@foreach($ratings as $r)
<option value="{{ $r->id }}">{{ $r->title }}</option>
@endforeach
</select>

<div class="card mt-3">
<div class="card-header d-flex justify-content-between">
<strong>Competencies</strong>
<button type="button" class="btn btn-success btn-sm" onclick="addCategory()">Add Category</button>
</div>
<div class="card-body" id="categoryWrapper"></div>
</div>

</div>

<div class="modal-footer">
<button class="btn btn-success">Save</button>
</div>

</div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('steps');

let catIndex=0;

function addCategory(){
 let html=`
 <div class="border p-2 mb-2">
 <select name="competencies[${catIndex}][competency_id]" class="form-control mb-1">
 @foreach($competencies as $c)
 <option value="{{ $c->id }}">{{ $c->name }}</option>
 @endforeach
 </select>

 <div id="items_${catIndex}"></div>
 <button type="button" class="btn btn-sm btn-primary" onclick="addItem(${catIndex})">+ Item</button>
 </div>`;
 document.getElementById('categoryWrapper').insertAdjacentHTML('beforeend',html);
 catIndex++;
}

function addItem(i){
 document.getElementById('items_'+i).insertAdjacentHTML('beforeend',`
 <div class="input-group mb-1">
 <input name="competencies[${i}][items][]" class="form-control">
 <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">X</button>
 </div>
 `);
}

function prepareHiddenHtml(){

 let levelHtml='<ul>';
 $('#level_select option:selected').each(function(){
   levelHtml+='<li>'+$(this).text()+'</li>';
 });
 levelHtml+='</ul>';
 $('#level_html').val(levelHtml);

 let ratingHtml='<ul>';
 $('#rating_select option:selected').each(function(){
   ratingHtml+='<li>'+$(this).text()+'</li>';
 });
 ratingHtml+='</ul>';
 $('#rating_html').val(ratingHtml);

 $('#competency_html').val($('#categoryWrapper').html());
}
</script>
@endpush
