@extends('auth_layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="login-container">

            <div class="login-card">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('adminlte/assets/img/logo.png') }}" alt="LMS Logo" height="80">
                </div>

                <h4 class="text-center mb-3">Create Account</h4>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text input-group-text-custom">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                                   placeholder="Your Name"
                                   required autofocus>
                        </div>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text input-group-text-custom">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   placeholder="example@gmail.com"
                                   required>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text input-group-text-custom">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password"
                                   name="password"
                                   class="form-control form-control-custom @error('password') is-invalid @enderror"
                                   placeholder="••••••••"
                                   required>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text input-group-text-custom">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control form-control-custom"
                                   placeholder="••••••••"
                                   required>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn login-btn w-100">
                        Register
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            Already have an account? Login
                        </a>
                    </div>
                </form>

                <div class="copyright">
                    Copyright © 2019-2020. All rights reserved
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
