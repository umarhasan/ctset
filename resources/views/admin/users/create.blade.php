@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1>Create New User</h1>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
    <div class="container-fluid">

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! </strong> There were some problems with your input:
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">User Information</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        </div>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="roles[]" id="roleSelect" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TRAINEE ONLY FIELDS --}}
                    <div id="traineeFields" style="display:none;" class="border p-3 rounded mb-3 bg-light">
                        <h5 class="mb-3">Trainee Details</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Sub Type</label>
                                <select name="sub_utype" class="form-select">
                                    <option value="">Select</option>
                                    <option value="1">Doctor</option>
                                    <option value="2">Master</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <select name="sem_id" class="form-select">
                                    <option value="">Select Semester</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            
                            <div class="col-md-12">
                                <label class="form-label">Signature Image</label>
                                <input type="file" name="signature_image" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- GENDER --}}
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Address"></textarea>
                    </div>

                    {{-- PROFILE IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control">
                    </div>
                    <div class="md-3">
                      <label class="form-label">Rotation</label>
                      <select name="rotation_id" class="form-select">
                          <option value="">Select Rotation</option>
                          @foreach($rotations as $rotation)
                              <option value="{{ $rotation->id }}">{{ $rotation->name }}</option>
                          @endforeach
                      </select>
                  </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-5">Create User</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById('roleSelect');
    const traineeFields = document.getElementById('traineeFields');

    function toggleFields() {
        if(roleSelect.value === 'Assessor'){
            traineeFields.style.display = 'block';
        } else {
            traineeFields.style.display = 'none';
        }
    }

    toggleFields();
    roleSelect.addEventListener('change', toggleFields);
});
</script>

@endsection