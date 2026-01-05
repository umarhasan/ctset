@extends('layouts.app')


@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-10">
          </div>
          <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Permissions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Permissions List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-header">
                <a class="btn btn-success" href="{{ route('permissions.create') }}"> Create New Permission</a>
                <!-- <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a> -->
                </div>
              <div class="card-body table-responsive">
                <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center align-middle w-100">
                    <thead class="table-light text-nowrap">
                  <tr>
                    <th>S.No</th>
                    <th>permission</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @if($permissions)
                  @php
                  $id =1;
                  @endphp
                  @foreach($permissions as $key =>  $permission)
                  <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $permission->name }}</td>
                      <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('permissions.edit',$permission->id) }}"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item"><i class="bx bx-trash-alt me-1"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </td>

                  </tr>
                  @endforeach
                  @endif
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>S.No</th>
                    <th>Roles</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>


@endsection
