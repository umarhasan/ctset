@extends('layouts.app')
<style>
  .small-box {
    position: relative;
}

.small-box .icon i {
    font-size: 70px;
    position: absolute;
    right: 15px;
    top: 20px;
    opacity: 0.3;
}

</style>
@section('title','Admin Dashboard')

@section('content')
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Admin Dashboard</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
           <div class="row">
            @foreach($roles as $index => $role)
              <div class="col-lg-3 col-6">
                <div class="small-box 
                  {{ $index % 4 == 0 ? 'text-bg-primary' : 
                    ($index % 4 == 1 ? 'text-bg-success' :
                    ($index % 4 == 2 ? 'text-bg-warning' : 'text-bg-danger')) }}">

                  <div class="inner">
                    <h3>{{ $role->users_count }}</h3>
                    <p>{{ ucfirst($role->name) }}</p>
                  </div>
                  <div class="icon">
                      <i class="bi bi-people-fill"></i>
                  </div>
                  
                  <a href="#"
                    class="small-box-footer link-light link-underline-opacity-0
                    link-underline-opacity-50-hover">
                    View {{ ucfirst($role->name) }} <i class="bi bi-arrow-right"></i>
                  </a>

                </div>
              </div>
            @endforeach
            </div>
            
          </div>
        </div>
      </main>
@endsection
