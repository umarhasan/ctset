@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Users List</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Semester</th>
                <th>Rotation</th>
                <th>Sub Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(',', $user->getRoleNames()->toArray()) }}</td>
                <td>{{ $user->semester->title ?? '-' }}</td>
                <td>{{ $user->rotation->title ?? '-' }}</td>
                <td>
                    @if($user->sub_utype == 1) Doctor
                    @elseif($user->sub_utype == 2) Master
                    @else - @endif
                </td>
                <td>
                    <a href="{{ route('users.edit',$user->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('users.destroy',$user->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete user?')" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection