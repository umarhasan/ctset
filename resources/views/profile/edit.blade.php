@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row justify-content-center align-items-start min-vh-100">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="profile-container">

            <!-- Profile Information Card -->
            <div class="login-card mb-4 shadow-sm rounded-3 p-4">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update Password Card -->
            <div class="login-card mb-4 shadow-sm rounded-3 p-4">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete User Card -->
            <div class="login-card shadow-sm rounded-3 p-4 border border-danger">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</div>
@endsection
