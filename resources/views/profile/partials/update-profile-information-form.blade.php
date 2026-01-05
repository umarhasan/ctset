<div class="">
    <div class="col-12">
        <div class="login-container">

            <div class="login-card">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Update your account's profile information and email address.
                    </p>
                </div>

                <!-- Email Verification Form -->
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <!-- Profile Update Form -->
                <form method="post" action="{{ route('profile.update') }}" class="mt-4">
                    @csrf
                    @method('patch')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus
                        >
                        @error('name')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="error-message mt-1">{{ $message }}</div>
                        @enderror

                        <!-- Verification Notice -->
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="text-sm mt-2 text-gray-800">
                                Your email address is unverified.
                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    Click here to re-send the verification email.
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        @endif
                    </div>

                    <!-- Save Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary w-100">Save</button>

                        @if (session('status') === 'profile-updated')
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
