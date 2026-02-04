@extends('auth_layouts.app')

@section('title', 'Login')

@section('content')
   <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="login-container">
                    <!-- University Header -->
                    <!-- Login Card -->
                    <div class="login-card">
                        <div class="text-center mb-4">
                            <img src="{{ asset('adminlte/assets/img/Medivisty-trans-logo.png') }}" alt="LMS Logo" height="200">
                        </div>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

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
                                           placeholder="admin@gmail.com"
                                           required autofocus>
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
                                <div class="password-note">Your strong password</div>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember me</label>
                                <span class="remember-note">(if this is a private computer)</span>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn login-btn">
                                Login
                            </button>
                        </form>

                        <!-- Copyright -->
                        <div class="copyright">
                            Copyright © 2019-2020. All rights reserved
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
