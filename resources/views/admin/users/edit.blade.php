@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Edit User</h3>

    <form action="{{ route('users.update',$user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- NAME --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>First Name</label>
                <input type="text" name="fname"
                       value="{{ explode(' ', $user->name)[0] ?? '' }}"
                       class="form-control">
            </div>

            <div class="col-md-6">
                <label>Last Name</label>
                <input type="text" name="lname"
                       value="{{ explode(' ', $user->name)[1] ?? '' }}"
                       class="form-control">
            </div>
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email"
                   value="{{ $user->email }}"
                   class="form-control">
        </div>

        {{-- PASSWORD OPTIONAL --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>New Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="col-md-6">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        {{-- ROLE --}}
        <div class="mb-3">
            <label>Role</label>
            <select name="roles[]" id="roleSelectEdit" class="form-select">
                @foreach($roles as $role)
                    <option value="{{ $role }}"
                        {{ in_array($role, $userRole) ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TRAINEE --}}
        <div id="traineeFieldsEdit" class="border p-3 mb-3 bg-light rounded">

            <h5>Trainee Details</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Sub Type</label>
                    <select name="sub_utype" class="form-select">
                        <option value="">Select</option>
                        <option value="1" {{ $user->sub_utype==1?'selected':'' }}>Doctor</option>
                        <option value="2" {{ $user->sub_utype==2?'selected':'' }}>Master</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Semester</label>
                    <select name="sem_id" class="form-select">
                        <option value="">Select</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}"
                                {{ $user->sem_id==$sem->id?'selected':'' }}>
                                {{ $sem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Signature Image</label>
                <input type="file" name="signature_image" class="form-control">

                @if($user->signature_image)
                    <img src="{{ asset('storage/signatures/'.$user->signature_image) }}"
                         width="120" class="mt-2">
                @endif
            </div>

        </div>

        {{-- GENDER --}}
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="1" {{ $user->gender==1?'selected':'' }}>Male</option>
                <option value="2" {{ $user->gender==2?'selected':'' }}>Female</option>
            </select>
        </div>

        {{-- ROTATION --}}
        <div class="mb-3">
            <label>Rotation</label>
            <select name="rotation_id" class="form-select">
                <option value="">Select</option>
                @foreach($rotations as $rotation)
                    <option value="{{ $rotation->id }}"
                        {{ $user->rotation_id==$rotation->id?'selected':'' }}>
                        {{ $rotation->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ADDRESS --}}
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ $user->address }}</textarea>
        </div>

        {{-- PROFILE IMAGE --}}
        <div class="mb-3">
            <label>Profile Image</label>
            <input type="file" name="profile_image" class="form-control">

            @if($user->profile_image)
                <img src="{{ asset('storage/profiles/'.$user->profile_image) }}"
                     width="120" class="mt-2">
            @endif
        </div>

        <div class="text-end">
            <button class="btn btn-success px-5">Update User</button>
        </div>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const roleSelect = document.getElementById('roleSelectEdit');
    const traineeFields = document.getElementById('traineeFieldsEdit');

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