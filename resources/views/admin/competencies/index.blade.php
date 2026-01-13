@extends('layouts.app')
@section('title','Competencies')

@section('content')
<div class="card card-primary card-outline">
 <div class="card-header d-flex justify-content-between">
  <h3>Competencies</h3>
    <div class="ms-auto">
        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">Add Competency</button>
    </div>
 </div>

 <div class="card-body">
  <table class="table table-bordered" id="tbl">
   <thead>
    <tr>
     <th>#</th><th>Name</th><th>Key</th><th>Seq</th><th>Desc</th><th>Act</th>
    </tr>
   </thead>
   <tbody>
    @foreach($competencies as $c)
    <tr>
     <td>{{ $loop->iteration }}</td>
     <td>{{ $c->name }}</td>
     <td>{{ $c->key }}</td>
     <td>{{ $c->sequence }}</td>
     <td>{{ Str::limit($c->description,40) }}</td>
     <td>
      <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $c->id }})">Edit</button>
      <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $c->id }})">Del</button>
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
     <input class="form-control mb-1" name="name" id="name" placeholder="Name" required>
     <small class="text-danger" id="err_name"></small>

     <input class="form-control mb-1" name="key" id="key" placeholder="Key" required>
     <small class="text-danger" id="err_key"></small>

     <input class="form-control mb-1" type="number" name="sequence" id="sequence" placeholder="Sequence" required>
     <small class="text-danger" id="err_sequence"></small>

     <textarea class="form-control mb-1" name="description" id="description" placeholder="Description" required></textarea>
     <small class="text-danger" id="err_description"></small>
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
        $('#modalTitle').text('Add Competency');
        new bootstrap.Modal('#masterModal').show();
    }

    window.openEditModal=id=>{
        $.get('competencies/'+id+'/edit', r=>{
            $('#record_id').val(r.id);
            $('#name').val(r.name);
            $('#key').val(r.key);
            $('#sequence').val(r.sequence);
            $('#description').val(r.description);
            $('#masterForm small.text-danger').text('');
            $('#modalTitle').text('Edit Competency');
            new bootstrap.Modal('#masterModal').show();
        });
    }

    $('#masterForm').submit(function(e){
        e.preventDefault();
        let id = $('#record_id').val();
        $('#masterForm small.text-danger').text(''); // clear previous errors

        $.ajax({
            url: id?'competencies/'+id:'competencies',
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
                    for(let field in errors){
                        $('#err_'+field).text(errors[field][0]);
                    }
                } else toastr.error('Something went wrong');
            }
        });
    });

    window.deleteRecord=id=>{
        if(!confirm('Delete?'))return;
        $.ajax({
            url:'competencies/'+id, method:'DELETE',
            data:{_token:'{{ csrf_token() }}'},
            success: ()=>location.reload()
        });
    }
});
</script>
@endpush
