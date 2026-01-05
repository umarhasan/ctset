<div class="row">
    <div class="col-md-12">
        <div class="login-container">

            <div class="login-card">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>

                <!-- Form -->
                <form method="post" action="{{ route('password.update') }}" class="mt-4">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            autocomplete="current-password"
                            required
                        >
                        @error('current_password')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password"
                            required
                        >
                        @error('password')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            autocomplete="new-password"
                            required
                        >
                        @error('password_confirmation')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary w-100">Save</button>

                        @if (session('status') === 'password-updated')
                            <span class="text-sm text-gray-600 ms-2" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                                Saved.
                            </span>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
