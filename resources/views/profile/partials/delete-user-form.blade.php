<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-12">
        <div class="login-container">

            <div class="login-card">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Delete Account</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Before deleting your account, please download any data you wish to retain.
                    </p>
                </div>

                <!-- Delete Button -->
                <div class="text-center">
                    <button
                        type="button"
                        class="btn btn-danger w-100"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                        Delete Account
                    </button>
                </div>

                <!-- Modal -->
                <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                    <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                        @csrf
                        @method('delete')

                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Are you sure you want to delete your account?
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Once your account is deleted, all resources and data will be permanently deleted.
                            Please enter your password to confirm.
                        </p>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Password"
                                class="form-control w-100 @error('password') is-invalid @enderror"
                                required
                            >
                            @error('password')
                                <div class="error-message mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-danger">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </x-modal>
            </div>

        </div>
    </div>
</div>
