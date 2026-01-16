@extends('layouts.app')
@section('title','Ratings')

@section('content')
<div class="card card-primary card-outline">
 <div class="card-header d-flex justify-content-between">
  <h3>Ratings</h3>
    <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Add Ratings</button>
    </div>

 </div>

 <div class="card-body">
  <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
   <thead>
    <tr>
     <th>#</th><th>Title</th><th>Score</th><th>Act</th>
    </tr>
   </thead>
   <tbody>
    @foreach($ratings as $r)
    <tr>
     <td>{{ $loop->iteration }}</td>
     <td>{{ $r->title }}</td>
     <td>{{ $r->score }}</td>
     <td>
      <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $r->id }})"><i class="fa fa-edit"></i></button>
      <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $r->id }})"><i class="fa fa-trash"></i></button>
     </td>
    </tr>
    @endforeach
   </tbody>
  </table>
 </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="masterModal">
 <div class="modal-dialog">
  <form id="masterForm">@csrf
   <input type="hidden" id="record_id">
   <div class="modal-content">
    <div class="modal-header bg-primary text-white">
     <h5 id="modalTitle"></h5>
     <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
     <input class="form-control mb-1" name="title" id="title" placeholder="Title" required>
     <small class="text-danger" id="err_title"></small>

     <input class="form-control mb-1" type="number" name="score" id="score" placeholder="Score" required>
     <small class="text-danger" id="err_score"></small>
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
<script>
$(function(){
 $('#tbl').DataTable();

 window.openCreateModal=()=>{
     $('#record_id').val(''); $('#masterForm')[0].reset();
     $('#masterForm small.text-danger').text('');
     $('#modalTitle').text('Add Rating');
     new bootstrap.Modal('#masterModal').show();
 }

 window.openEditModal=id=>{
     $.get('ratings/'+id+'/edit', r=>{
         $('#record_id').val(r.id);
         $('#title').val(r.title);
         $('#score').val(r.score);
         $('#masterForm small.text-danger').text('');
         $('#modalTitle').text('Edit Rating');
         new bootstrap.Modal('#masterModal').show();
     });
 }

 $('#masterForm').submit(function(e){
     e.preventDefault();
     let id = $('#record_id').val();
     $('#masterForm small.text-danger').text('');

     $.ajax({
         url: id?'ratings/'+id:'ratings',
         method:'POST',
         data: $(this).serialize() + (id?'&_method=PUT':''),
         success: function(res){
             toastr.success('Saved successfully');
             $('#masterModal').modal('hide');
             setTimeout(()=>location.reload(),700);
         },
         error: function(xhr){
             if(xhr.status==422){
                 let errors = xhr.responseJSON.errors;
                 for(let f in errors) $('#err_'+f).text(errors[f][0]);
             } else toastr.error('Something went wrong');
         }
     });
 });

 window.deleteRecord=id=>{
     if(!confirm('Delete?'))return;
     $.ajax({
         url:'ratings/'+id, method:'DELETE',
         data:{_token:'{{ csrf_token() }}'},
         success: ()=>location.reload()
     });
 }
});
</script>
@endpush
