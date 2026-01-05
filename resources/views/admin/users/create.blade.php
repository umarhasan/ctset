@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-9">
            <h1>Create New User</h1>
          </div>
          <div class="col-sm-3">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create New User</li>
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
                  <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <input class="form-control mb-2" name="name" placeholder="Name" required>

                        <input class="form-control mb-2" type="email" name="email" placeholder="Email" required>

                        <input class="form-control mb-2" type="password" name="password" placeholder="Password" required>

                        <input class="form-control mb-2" type="password" name="password_confirmation" placeholder="Confirm Password" required>

                        <select name="roles[]" class="form-control mb-3" required>
                        @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                        </select>

                        <button class="btn btn-primary">Create User</button>
                    </form>
                  </div>
              </div>
          </div>
        </div>
    </div>
</section>
</div>
@endsection
