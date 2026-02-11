@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit User</h3>

    <form action="{{ route('users.update',$user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label>First Name</label>
                <input type="text" name="fname" value="{{ explode(' ', $user->name)[0] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Last Name</label>
                <input type="text" name="lname" value="{{ explode(' ', $user->name)[1] ?? '' }}" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="roles[]" id="roleSelectEdit" class="form-select">
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Trainee Fields --}}
        <div id="traineeFieldsEdit" class="border p-3 rounded mb-3 bg-light">
            <div class="row g-3 mb-3">
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
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $user->sem_id==$sem->id?'selected':'' }}>
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
                    <img src="{{ asset('storage/signatures/'.$user->signature_image) }}" width="100" class="mt-2">
                @endif
            </div>
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="1" {{ $user->gender==1?'selected':'' }}>Male</option>
                <option value="2" {{ $user->gender==2?'selected':'' }}>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Rotation</label>
            <select name="rotation_id" class="form-select">
                @foreach($rotations as $rotation)
                    <option value="{{ $rotation->id }}" {{ $user->rotation_id==$rotation->id?'selected':'' }}>
                        {{ $rotation->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="2">{{ $user->address }}</textarea>
        </div>

        <div class="mb-3">
            <label>Profile Image</label>
            <input type="file" name="profile_image" class="form-control">
            @if($user->profile_image)
                <img src="{{ asset('storage/profiles/'.$user->profile_image) }}" width="100" class="mt-2">
            @endif
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success px-5">Update User</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const roleSelectEdit = document.getElementById('roleSelectEdit');
    const traineeFieldsEdit = document.getElementById('traineeFieldsEdit');

    function toggleFields() {
        if(roleSelectEdit.value.toLowerCase() === 'Assessor'){
            traineeFieldsEdit.style.display = 'block';
        } else {
            traineeFieldsEdit.style.display = 'none';
        }
    }

    toggleFields();
    roleSelectEdit.addEventListener('change', toggleFields);
});
</script>
@endsection