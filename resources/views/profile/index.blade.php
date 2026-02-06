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
                        <a class="nav-link" data-tab="tab_{{ $tab->id }}" href="#" style="">
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

            {{-- GENERAL TAB --}}
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
                        <div class="col-md-6">
                            <label>Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">
                            <img src="{{ $user->profile_image ? route('user.profile.stream', $user->profile_image) : asset('adminlte/assets/img/avatar.png') }}" width="100" class="mt-2">
                        </div>

                        <div class="col-md-6">
                            <label>Signature Image</label>
                            <input type="file" name="signature_image" class="form-control">
                            <img src="{{ $user->signature_image ? route('user.signature.stream', $user->signature_image) : asset('adminlte/assets/img/default-signature.png') }}" width="150" class="mt-2">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Bio</label>
                        <textarea name="bio" id="bio" class="form-control">{{ $user->bio ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

            {{-- EXISTING TABS --}}
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

                        <textarea id="editor_{{ $tab->id }}" name="tabsdesc" class="form-control">{{ $tab->tabsdesc }}</textarea>

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

let newIndex = 0;
const csrfToken = "{{ csrf_token() }}";
let summernoteInstances = {};

$(document).ready(function(){

    $('#bio').summernote({ height:200 });

    @foreach($tabs as $tab)
        initSummernote('editor_{{ $tab->id }}');
    @endforeach

    $('#profileTabs .nav-link:first').click();
    setupTabFormEvents();
});

function initSummernote(id){
    summernoteInstances[id] = $('#'+id).summernote({ height:250 });
}

function getContent(id){
    return $('#'+id).summernote('code');
}

$(document).on('click','#profileTabs .nav-link',function(e){
    e.preventDefault();

    $('.nav-link').removeClass('active');
    $('.tab-content').removeClass('active').hide();

    $(this).addClass('active');
    let tabId = $(this).data('tab');

    $('#'+tabId).addClass('active').show();

    let editorId = $('#'+tabId).find('textarea[id^="editor_"]').attr('id');
    if(editorId && !summernoteInstances[editorId]){
        initSummernote(editorId);
    }
});

$('#generalForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);
    formData.set('bio',$('#bio').summernote('code'));

    $.ajax({
        url:"{{ route('profile.update') }}",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        success:()=>location.reload()
    });
});

function setupTabFormEvents(){
    $(document).off('submit','.tabForm').on('submit','.tabForm',function(e){
        e.preventDefault();

        let form=$(this);
        let tabId=form.data('tabid');
        let editorId='editor_'+tabId;

        $.post("{{ route('profile.tab.save') }}",{
            _token:csrfToken,
            tabid:form.find('[name=tabid]').val(),
            tabname:form.find('[name=tabname]').val(),
            profile_type:form.find('[name=profile_type]').val(),
            tabsdesc:getContent(editorId)
        },()=>location.reload());
    });
}

function addNewTab(){

    newIndex++;
    let id='new_'+newIndex;
    let tempId='temp_'+newIndex;

    $('#profileTabs').append(`
        <li class="nav-item" id="tab_li_${id}">
            <a class="nav-link" data-tab="${tempId}" href="#">New Tab</a>
        </li>
    `);

    $('.card-body').append(`
        <div class="tab-content" id="${tempId}">
            <form class="tabForm" data-tabid="${tempId}">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="tabid" value="0">

                <div class="mb-2">
                    <label>Tab Name</label>
                    <input type="text" name="tabname" class="form-control" value="New Tab">
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
                    <button type="button" class="btn btn-danger ms-2" onclick="deleteNewTab('${tempId}','${id}')">Cancel</button>
                </div>
            </form>
        </div>
    `);

    initSummernote('editor_'+tempId);
    $('#tab_li_'+id+' .nav-link').click();
}

function deleteNewTab(tempId,id){
    $('#tab_li_'+id).remove();
    $('#'+tempId).remove();
    $('#profileTabs .nav-link:first').click();
}

function deleteTab(id){
    if(!confirm('Delete tab?')) return;

    $.ajax({
        url:"{{ url('profile/tab') }}/"+id,
        type:"DELETE",
        data:{_token:csrfToken},
        success:()=>location.reload()
    });
}

</script>

<style>
.tab-content{
    display:none;
}
.tab-content.active{
    display:block;
}

/* normal tabs */
#profileTabs .nav-link{
    cursor:pointer;
    color:#495057;
}

/* ACTIVE TAB — BLACK */
#profileTabs.nav-tabs .nav-link.active{
    background-color:#000 !important;
    color:#fff !important;
    border-color:#000 !important;
}

/* hover */
#profileTabs.nav-tabs .nav-link:hover{
    background-color:#111;
    color:#fff;
}
</style>
@endpush