@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-10">
            <h1>Edit User</h1>
          </div>
          <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit / User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
    <div class="container-fluid">
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
               @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
               @endforeach
            </ul>
          </div>
        @endif
        <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                  <form action="{{ route('users.update',$user->id) }}" method="POST">
@csrf
@method('PUT')

<input class="form-control mb-2" name="name" value="{{ $user->name }}">

<input class="form-control mb-2" name="email" value="{{ $user->email }}">

<input class="form-control mb-2" type="password" name="password" placeholder="New Password">

<input class="form-control mb-2" type="password" name="password_confirmation" placeholder="Confirm Password">

<select name="roles[]" class="form-control mb-3">
@foreach($roles as $role)
<option value="{{ $role }}" {{ in_array($role,$userRole) ? 'selected':'' }}>
{{ $role }}
</option>
@endforeach
</select>

<button class="btn btn-success">Update User</button>
</form>
                  </div>
              </div>
          </div>
        </div>
    </div>
</section>
</div>
@endsection
