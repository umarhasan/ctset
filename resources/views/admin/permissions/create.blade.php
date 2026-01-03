@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-10">
            <h1>Create Permission</h1>
          </div>
          <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Permission</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                  <form method="post" action="{{route('permissions.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input name="name" placeholder="Name" class="form-control" required>
                            </div>
                        </div>
                        </br></br></br></br>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                  </form>
                  </div>
                  <div class="card-body">
                    <table id="example" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="10%">Name</th>
                            <th width="10%">Action</th>
                          </tr>
                      </thead>
                        <tbody>
                            @forelse ($list as $key => $item)

                              <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <form action="{{route('permissions.destroy',$item->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Are You Sure Want to Delete This.?')" class="btn btn-danger"><i class="fa fa-delete"></i>Delete</button>
                                    </form>
                                </td>
                              </tr>
                              @empty
                            @endforelse
                        </tbody>
                        </table>
                    </div>
              </div>
          </div>
        </div>
    </div>
</section>
  </div>
  <script>

$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
  theme: 'bootstrap4'
})

  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": []
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<script type="text/javascript">

 var APP_URL = {!! json_encode(url('/')) !!}




</script>
<style>
  .form-check-input{
    border-radius: 0 !important;
    height: 20px;
    width: 20px;
    margin:0;
  }

  .form-group strong{
    margin: 0 0 10px;
    width: fit-content;
    display: block;
  }

  .my-txt-box{
    padding: 0 0 10px;
  }

  .my-label{
    padding-left: 30px;
    text-transform:capitalize;
  }
  </style>
@endsection
