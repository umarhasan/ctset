@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3 fw-bold">Profile</h3>

    <div class="card card-outline card-primary">
        <div class="card-header d-flex align-items-center">
            <ul class="nav nav-tabs card-header-tabs" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-tab="general" href="#">General</a>
                </li>

                @foreach($tabs as $tab)
                    <li class="nav-item" id="tab_li_{{ $tab->id }}">
                        <a class="nav-link" data-tab="tab_{{ $tab->id }}" href="#">
                            {{ $tab->tabname }}
                            @if($tab->profile_type == 'PU')
                                <span class="badge bg-danger ms-1">P</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <button class="btn btn-success btn-sm ms-auto" onclick="addNewTab()">+</button>
        </div>

        <div class="card-body">

            {{-- ================= GENERAL TAB ================= --}}
            <div class="tab-content active" id="general">
                <form id="generalForm" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                        </div>

                        <div class="col-md-6">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="New password">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label>Profile Image</label><br>
                            <input type="file" name="profile_image" class="form-control">
                            <img src="{{ $user->profile_image ? route('user.profile.stream', $user->profile_image) : asset('adminlte/assets/img/avatar.png') }}" width="100" class="mb-2">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Signature Image</label><br>
                            <input type="file" name="signature_image" class="form-control">
                            <img src="{{ $user->signature_image ? route('user.signature.stream', $user->signature_image) : asset('adminlte/assets/img/default-signature.png') }}" width="150" class="mb-2">
                        </div>
                    </div>

                        @if($user->hasAnyRole(['doctor','trainee']))
                        <div class="col-md-6">
                            <label>Signature Image</label>
                            <input type="file" name="signature_image" class="form-control">
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label>Bio</label>
                        <textarea name="bio" id="bio" class="form-control">{{ $user->bio ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

            {{-- ================= EXISTING TABS ================= --}}
            @foreach($tabs as $tab)
                <div class="tab-content" id="tab_{{ $tab->id }}">
                    <form class="tabForm" data-tabid="{{ $tab->id }}">
                        @csrf
                        <input type="hidden" name="tabid" value="{{ $tab->id }}">

                        <div class="mb-2">
                            <label>Tab Name</label>
                            <input type="text" name="tabname" class="form-control" value="{{ $tab->tabname }}">
                        </div>

                        <div class="mb-2">
                            <label>Profile Type</label>
                            <select name="profile_type" class="form-control">
                                <option value="PU" @selected($tab->profile_type=='PU')>Public</option>
                                <option value="PR" @selected($tab->profile_type=='PR')>Private</option>
                            </select>
                        </div>

                        <textarea id="editor_{{ $tab->id }}" name="tabsdesc" class="form-control">
                            {{ $tab->tabsdesc }}
                        </textarea>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger ms-2" onclick="deleteTab({{ $tab->id }})">Delete</button>
                        </div>
                    </form>
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
/* ================= GLOBAL VARIABLES ================= */
let newIndex = 0;
const csrfToken = "{{ csrf_token() }}";
let summernoteInstances = {};

/* ================= INITIALIZATION ================= */
$(document).ready(function() {
    // Initialize Summernote for Bio field
    $('#bio').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    
    // Initialize Summernote for existing tabs
    @foreach($tabs as $tab)
        initSummernoteEditor('editor_{{ $tab->id }}');
    @endforeach
    
    // Activate first tab on load
    $('#profileTabs .nav-link:first').click();
    
    // Setup form events
    setupTabFormEvents();
});

/* ================= INITIALIZE SUMMERNOTE EDITOR ================= */
function initSummernoteEditor(elementId) {
    summernoteInstances[elementId] = $('#' + elementId).summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['font', ['superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
}

/* ================= GET SUMMERNOTE CONTENT ================= */
function getSummernoteContent(elementId) {
    return $('#' + elementId).summernote('code');
}

/* ================= SET SUMMERNOTE CONTENT ================= */
function setSummernoteContent(elementId, content) {
    $('#' + elementId).summernote('code', content);
}

/* ================= TAB SWITCH ================= */
$(document).on('click', '#profileTabs .nav-link', function(e){
    e.preventDefault();
    
    // Remove active class from all tabs
    $('.nav-link').removeClass('active');
    $('.tab-content').removeClass('active').hide();
    
    // Add active class to clicked tab
    $(this).addClass('active');
    
    // Show corresponding content
    let tabId = $(this).data('tab');
    $('#' + tabId).addClass('active').show();
    
    // Reinitialize Summernote for the active tab (if needed)
    let editorId = $(this).closest('.tab-content').find('textarea[id^="editor_"]').attr('id');
    if (editorId && !summernoteInstances[editorId]) {
        initSummernoteEditor(editorId);
    }
});

/* ================= GENERAL FORM SAVE ================= */
$('#generalForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);
    // Get Summernote content for bio
    formData.set('bio', $('#bio').summernote('code'));

    $.ajax({
        url: "{{ route('profile.update') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success) {
                alert('Profile saved successfully!');
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Error saving profile: ' + xhr.responseText);
        }
    });
});

/* ================= TAB FORM EVENT SETUP ================= */
function setupTabFormEvents() {
    $(document).off('submit', '.tabForm').on('submit', '.tabForm', function(e) {
        e.preventDefault();
        saveTabForm($(this));
    });
}

/* ================= SAVE TAB FORM ================= */
function saveTabForm(form) {
    let tabId = form.data('tabid') || form.find('[name="tabid"]').val();
    let editorId = 'editor_' + tabId;
    
    // Prepare form data
    let formData = {
        _token: csrfToken,
        tabid: form.find('[name="tabid"]').val(),
        tabname: form.find('[name="tabname"]').val(),
        profile_type: form.find('[name="profile_type"]').val(),
        tabsdesc: getSummernoteContent(editorId)
    };
    
    console.log('Saving tab:', formData);
    
    // If tabid is 0, it's a new tab
    let url = "{{ route('profile.tab.save') }}";
    
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        success: function(response) {
            console.log('Response:', response);
            if (response.success) {
                alert('Tab saved successfully!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            console.log('Error:', xhr);
            alert('Error saving tab: ' + xhr.responseText);
        }
    });
}

/* ================= ADD NEW TAB ================= */
function addNewTab() {
    newIndex++;
    let id = 'new_' + newIndex;
    let tempId = 'temp_' + newIndex;
    
    // Add new tab to navigation
    $('#profileTabs').append(`
        <li class="nav-item" id="tab_li_${id}">
            <a class="nav-link" data-tab="${tempId}" href="#">
                New Tab
                <span class="badge bg-warning ms-1">New</span>
            </a>
        </li>
    `);

    // Add new tab content
    $('.card-body').append(`
        <div class="tab-content" id="${tempId}">
            <form class="tabForm" data-tabid="${tempId}">
                @csrf
                <input type="hidden" name="tabid" value="0">

                <div class="mb-2">
                    <label>Tab Name</label>
                    <input type="text" name="tabname" class="form-control" placeholder="Tab name" value="New Tab">
                </div>

                <div class="mb-2">
                    <label>Profile Type</label>
                    <select name="profile_type" class="form-control">
                        <option value="PU">Public</option>
                        <option value="PR">Private</option>
                    </select>
                </div>

                <textarea id="editor_${tempId}" name="tabsdesc" class="form-control"></textarea>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-danger ms-2" onclick="deleteNewTab('${tempId}', '${id}')">Cancel</button>
                </div>
            </form>
        </div>
    `);

    // Initialize Summernote for new tab
    initSummernoteEditor('editor_' + tempId);
    
    // Switch to the new tab
    $(`#tab_li_${id} .nav-link`).click();
}

/* ================= DELETE NEW TAB (without saving) ================= */
function deleteNewTab(tempId, id) {
    if(confirm('Are you sure you want to cancel creating this tab?')) {
        $('#tab_li_' + id).remove();
        $('#' + tempId).remove();
        
        // Switch back to general tab
        $('#profileTabs .nav-link:first').click();
    }
}

/* ================= DELETE EXISTING TAB ================= */
function deleteTab(id) {
    if(!confirm('Are you sure you want to delete this tab?')) return;

    $.ajax({
        url: "{{ url('profile/tab') }}/" + id,
        type: "DELETE",
        data: {
            _token: csrfToken
        },
        success: function(response) {
            if (response.success) {
                alert('Tab deleted successfully!');
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Error deleting tab: ' + xhr.responseText);
        }
    });
}
</script>

<style>
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.nav-link {
    cursor: pointer;
}
.nav-link.active {
    background-color: #007bff;
    color: white !important;
}
.note-editor.note-frame {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}
</style>
@endpush