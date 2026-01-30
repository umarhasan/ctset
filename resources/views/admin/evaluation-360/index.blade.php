@extends('layouts.app')
@section('content')

<style>
/* Card hover effect */
.card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
</style>

@role('Admin')
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h4 class="mb-0">360 Evaluation Forms</h4>
         @can('evaluation-360-create')
            <div class="ms-auto">
                <button class="btn btn-light btn-sm" onclick="openCreate()"><i class="bi bi-plus-lg"></i> Add 360 Evaluation</button>
            </div>
        @endcan
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="text-center" width="300">Actions</th>
                </tr>
            </thead>
            <tbody id="formTable"></tbody>
        </table>
    </div>
</div>

{{-- CREATE/EDIT MODAL --}}
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="mainForm">
            @csrf
            <input type="hidden" id="form_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="mb-0">Create Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Form Title</label>
                        <input type="text" class="form-control" id="form_title" placeholder="Enter form title" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Status</label>
                        <select id="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Sections</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addSection()">
                            <i class="bi bi-plus-lg"></i> Add Section
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th class="text-center" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sectionsContainer"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success"><i class="bi bi-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SHARE MODAL --}}
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="shareForm">
            @csrf
            <input type="hidden" id="share_form_id">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="mb-0">Share 360 Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Evaluator Name</label>
                        <input type="text" id="share_name" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (optional)</label>
                        <input type="email" id="share_email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone (optional)</label>
                        <input type="text" id="share_phone" class="form-control" placeholder="Enter phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Details / Instructions</label>
                        <textarea id="share_details" class="form-control" placeholder="Optional details"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Share</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 id="viewTitle" class="mb-0">View Form</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewBody"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- TEMPLATES --}}
<template id="sectionTemplate">
<tr class="section-row">
    <td><input type="text" class="form-control section-title" placeholder="Section Title"></td>
    <td class="text-center">
        <button type="button" class="btn btn-outline-primary btn-sm me-1" onclick="toggleExpand(this)">+</button>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSection(this)">🗑</button>
    </td>
</tr>
</template>

<template id="expandTemplate">
<tr class="expand-row bg-light">
    <td>
        <input type="text" class="form-control mb-2 subtitle" placeholder="Sub Title">
        <div class="row text-center g-2">
            <div class="col-md-4">
                <small>1-5</small>
                <input type="text" class="form-control mt-1 col1">
            </div>
            <div class="col-md-4">
                <small>6-7</small>
                <input type="text" class="form-control mt-1 col2">
            </div>
            <div class="col-md-4">
                <small>UE</small>
                <input type="text" class="form-control mt-1 ue">
            </div>
        </div>
    </td>
    <td></td>
</tr>
</template>
@endrole
@endsection

@push('scripts')
<script>
let viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
@role('Admin')
let modal = new bootstrap.Modal(document.getElementById('formModal'));
let shareModal = new bootstrap.Modal(document.getElementById('shareModal'));

/* =================== LOAD TABLE =================== */
function loadTable(){
    $.get('evaluation-360', res => {
        let html = '';
        res.forEach(r => {
            html += `
            <tr>
                <td>${r.id}</td>
                <td>${r.title}</td>
                <td>${r.status}</td>
                <td>
                    @can('evaluation-360-update')
                        <button class="btn btn-warning btn-sm" onclick="openEdit(${r.id})">Edit</button>
                    @endcan
                    @can('evaluation-360-update')
                        <button class="btn btn-info btn-sm" onclick="openView(${r.id})">View</button>
                    @endcan
                    @can('evaluation-360-delete')
                        <button class="btn btn-danger btn-sm" onclick="removeForm(${r.id})">Delete</button>
                    @endcan
                    @can('evaluation-360-response')
                        <a href="evaluation-360/${r.id}/responses" class="btn btn-secondary btn-sm">Responses</a>
                    @endcan
                    @can('evaluation-360-share')
                        <button class="btn btn-success btn-sm" onclick="openShare(${r.id})">Share</button>
                    @endcan
                </td>
            </tr>`;
        });
        $('#formTable').html(html);
    });
}
loadTable();

/* =================== CREATE/EDIT FORM =================== */
function openCreate(){
    $('#modalTitle').text('Create Form');
    $('#mainForm')[0].reset();
    $('#form_id').val('');
    $('#sectionsContainer').empty();
    addSection();
    modal.show();
}

function openEdit(id){
    $.get(`evaluation-360/${id}/edit`, res => {
        $('#modalTitle').text('Edit Form');
        $('#form_id').val(res.id);
        $('#form_title').val(res.title);
        $('#status').val(res.status || 'active');
        $('#sectionsContainer').empty();
        res.sections.forEach(s => {
            addSection();
            let row = $('.section-row').last();
            row.find('.section-title').val(s.section_title);
            toggleExpand(row.find('button')[0]);
            let exp = row.next('.expand-row');
            exp.find('.subtitle').val(s.subtitle);
            exp.find('.col1').val(s.col_1_5);
            exp.find('.col2').val(s.col_6_7);
            exp.find('.ue').val(s.ue);
        });
        modal.show();
    });
}

function addSection(){ $('#sectionsContainer').append($('#sectionTemplate').html()); }
function toggleExpand(btn){
    let row = $(btn).closest('tr');
    if(row.data('open')){
        row.next('.expand-row').remove(); row.data('open', false); $(btn).text('+');
    } else { row.after($('#expandTemplate').html()); row.data('open', true); $(btn).text('-'); }
}
function removeSection(btn){ let row=$(btn).closest('tr'); if(row.data('open')) row.next('.expand-row').remove(); row.remove(); }

$('#mainForm').submit(function(e){
    e.preventDefault();
    let id = $('#form_id').val();
    let url = id ? `evaluation-360/${id}` : `evaluation-360`;
    let sections = [];
    $('.section-row').each(function(){
        let open=$(this).data('open'), exp=open?$(this).next('.expand-row'):null;
        sections.push({ title: $(this).find('.section-title').val(),
            subtitle: exp?exp.find('.subtitle').val():'',
            col_1_5: exp?exp.find('.col1').val():'',
            col_6_7: exp?exp.find('.col2').val():'',
            ue: exp?exp.find('.ue').val():''
        });
    });
    $.post(url,{ _token:'{{ csrf_token() }}', _method:id?'PUT':'POST', title:$('#form_title').val(), status:$('#status').val(), sections }, ()=>{
        modal.hide(); loadTable();
    });
});

function removeForm(id){
    if(!confirm('Are you sure you want to delete this form?')) return;
    $.post(`evaluation-360/${id}`, { _token:'{{ csrf_token() }}', _method:'DELETE' }, ()=>loadTable());
}

/* =================== VIEW FORM =================== */
function openView(id){
    $.get(`evaluation-360/${id}/edit`, res => {
        $('#viewTitle').text('View Form - '+res.title);
        let html=`<p><strong>Title:</strong> ${res.title}</p><p><strong>Status:</strong> ${res.status}</p><h5 class="mt-3">Sections:</h5>`;
        res.sections.forEach(s=>{
            html+=`<div class="card p-2 mb-2">
                <p><strong>Section:</strong> ${s.section_title}</p>
                <p><strong>Sub Title:</strong> ${s.subtitle}</p>
                <div class="row text-center">
                    <div class="col-md-4"><small>1-5:</small> ${s.col_1_5}</div>
                    <div class="col-md-4"><small>6-7:</small> ${s.col_6_7}</div>
                    <div class="col-md-4"><small>UE:</small> ${s.ue}</div>
                </div></div>`;
        });
        $('#viewBody').html(html); viewModal.show();
    });
}

/* =================== SHARE FORM =================== */
function openShare(formId){
    $('#share_form_id').val(formId);
    $('#share_name,#share_email,#share_phone,#share_details').val('');
    shareModal.show();
}

$('#shareForm').submit(function(e){
    e.preventDefault();
    let formId = $('#share_form_id').val();
    $.post(`evaluation-360/${formId}/share`, {
        _token:'{{ csrf_token() }}',
        name:$('#share_name').val(),
        email:$('#share_email').val(),
        phone:$('#share_phone').val(),
        details:$('#share_details').val()
    }, function(res){
        shareModal.hide();
        alert('Form shared! Link:\n' + res.link);
        console.log('Share link:', res.link);
    });
});
@endrole
</script>
@endpush
