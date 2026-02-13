@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Create New User</h1>
        </div>
    </section>

    <section class="content">
    <div class="container-fluid">

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>User Information</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- NAME --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>First Name *</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Last Name *</label>
                            <input type="text" name="lname" class="form-control" required>
                        </div>
                    </div>

                    {{-- EMAIL + PASSWORD --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-3">
                        <label>Role *</label>
                        <select name="roles[]" id="roleSelect" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TRAINEE FIELDS --}}
                    <div id="traineeFields" style="display:none;" class="border p-3 mb-3 bg-light rounded">

                        <h5>Trainee Details</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Sub Type</label>
                                <select name="sub_utype" class="form-select">
                                    <option value="">Select</option>
                                    <option value="1">Doctor</option>
                                    <option value="2">Master</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Semester</label>
                                <select name="sem_id" class="form-select">
                                    <option value="">Select</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Signature Image</label>
                            <input type="file" name="signature_image" class="form-control">
                        </div>
                    </div>

                    {{-- GENDER --}}
                    <div class="mb-3">
                        <label>Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>

                    {{-- ROTATION --}}
                    <div class="mb-3">
                        <label>Rotation</label>
                        <select name="rotation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($rotations as $rotation)
                                <option value="{{ $rotation->id }}">{{ $rotation->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ADDRESS --}}
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>

                    {{-- PROFILE IMAGE --}}
                    <div class="mb-3">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image" class="form-control">
                    </div>

                    <div class="text-end">
                        <button class="btn btn-success px-5">Create User</button>
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

    function toggleFields(){
        if(roleSelect.value === 'Assessor' || roleSelect.value === 'Consultant'){
            traineeFields.style.display = 'block';
        }else{
            traineeFields.style.display = 'none';
        }
    }

    toggleFields();
    roleSelect.addEventListener('change', toggleFields);

});
</script>

@endsection