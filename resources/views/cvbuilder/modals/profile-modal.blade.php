<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- ===== HEADER ===== --}}
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    {{ $cv->profile ? 'Edit' : 'Add' }} Profile Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- ===== FORM ===== --}}
            <form method="POST"
                  action="{{ $cv->profile ? route('cv-profile.update', $cv->profile->id) : route('cv-profile.store') }}"
                  enctype="multipart/form-data"
                  id="profileForm">

                @csrf
                @if($cv->profile)
                    @method('PUT')
                @endif

                <input type="hidden" name="cv_id" value="{{ $cv->id }}">

                <div class="modal-body">

                    {{-- ================= PROFILE IMAGE ================= --}}
                    @php
                        $profileImage =
                            $cv->profile->profile_image
                            ?? $cv->user->profile_image
                            ?? null;
                    @endphp

                    <div class="text-center mb-4">
                        <img
                            id="profilePreview"
                            src="{{ $profileImage
                                ? route('user.profile.stream', $profileImage)
                                : asset('adminlte/assets/img/avatar.png') }}"
                            width="120"
                            height="120"
                            class="rounded-circle shadow mb-2"
                            style="object-fit:cover;"
                        >

                        <div class="mt-2">
                            <input type="file"
                                   name="profile_image"
                                   class="form-control"
                                   accept="image/*">
                        </div>

                        <small class="text-muted">
                            JPG / PNG • Max 2MB
                        </small>
                    </div>

                    <hr>

                    {{-- ================= BASIC INFO ================= --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text"
                                   name="full_name"
                                   class="form-control"
                                   required
                                   value="{{ $cv->user->name }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $cv->user->email }}">
                        </div>
                    </div>

                    {{-- ================= ACADEMIC INFO ================= --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">University / Institution</label>
                            <input type="text"
                                   name="university"
                                   class="form-control"
                                   value="{{ $cv->profile->university ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Class Year</label>
                            <input type="text"
                                   name="class_year"
                                   class="form-control"
                                   value="{{ $cv->profile->class_year ?? '' }}">
                        </div>
                    </div>

                    {{-- ================= INTEREST ================= --}}
                    <div class="mb-3">
                        <label class="form-label">Primary Interest / Specialty</label>
                        <input type="text"
                               name="primary_interest"
                               class="form-control"
                               value="{{ $cv->profile->primary_interest ?? '' }}">
                    </div>

                    {{-- ================= PHONE ================= --}}
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ $cv->profile->phone ?? '' }}">
                    </div>

                </div>

                {{-- ===== FOOTER ===== --}}
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-info text-white">
                        {{ $cv->profile ? 'Update' : 'Create' }} Profile
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
