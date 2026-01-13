
@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Longitudinal Requirement Forms</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Longitudinal Requirement Forms
            </button>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover" id="requirementsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Course Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requirements as $requirement)
                <tr>
                    <td>{{ $requirement->id }}</td>
                    <td>{{ $requirement->title }}</td>
                    <td>{{ $requirement->course_title }}</td>
                    <td>
                        @if($requirement->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $requirement->created_at->format('Y-m-d') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $requirement->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $requirement->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $requirements->links() }}
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="masterModal">
    <div class="modal-dialog modal-xl">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="modal-title text-center w-100">LONGITUDINAL REQUIREMENT FORM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                    <!-- Main Form Title -->
                    <div class="row mb-3">
                        <div class="col-md-12 text-center mb-3">
                            <h3 class="fw-bold" style="color: #2c3e50;">LONGITUDINAL REQUIREMENT FORM</h3>
                        </div>
                    </div>

                    <!-- Title and Course Title -->
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Form Title:</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg"
                                   placeholder="Enter form title" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Course Title:</label>
                            <input type="text" name="course_title" id="course_title" class="form-control"
                                   placeholder="e.g., MASTER IN SURGICAL SCIENCES M.SURG.SC (CT)" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION A: HOSPITAL ATTACHMENT ACTIVITIES -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">A. HOSPITAL ATTACHMENT ACTIVITIES</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSubSection('sectionA')">
                                        <i class="fa fa-plus"></i> Add Section
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSection('sectionA')">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card section-content" id="sectionAContent">
                                <div class="card-body">
                                    <div class="sub-sections-container" id="sectionASubSections">
                                        <!-- Default Sub-Section -->
                                        <div class="sub-section-item mb-4">
                                            <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2 fw-bold">CARDIOLOGY ROTATION</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleSubSection(this)">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" title="Move Up" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" title="Move Down" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sub-section-body">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Title</th>
                                                            <th width="100">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="items-container">
                                                        <tr class="item-item">
                                                            <td class="text-center align-middle"><strong>1.</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm item-title"
                                                                       placeholder="e.g., Transthoracic Echocardiography (TTE)" required>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input item-checkbox" type="checkbox">
                                                                    <label class="form-check-label">✓</label>
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-outline-primary btn-sm add-item">
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION B: SUMMARY TABLE OF OPERATION THEATRE/PROCEDURE REQUIREMENT -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">B. SUMMARY TABLE OF OPERATION THEATRE/PROCEDURE REQUIREMENT</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSubSection('sectionB')">
                                        <i class="fa fa-plus"></i> Add Section
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSection('sectionB')">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card section-content" id="sectionBContent">
                                <div class="card-body">
                                    <div class="sub-sections-container" id="sectionBSubSections">
                                        <!-- Default Sub-Section -->
                                        <div class="sub-section-item mb-4">
                                            <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2 fw-bold">Cardiothoracic Surgery Rotation</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleSubSection(this)">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" title="Move Up" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" title="Move Down" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sub-section-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Table Columns:</label>
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control form-control-sm table-column"
                                                                   placeholder="e.g., A/B" value="A/B">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control form-control-sm table-column"
                                                                   placeholder="e.g., C/D" value="C/D">
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Title</th>
                                                            <th class="text-center" width="80">A/B</th>
                                                            <th class="text-center" width="80">C/D</th>
                                                            <th width="100">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="items-container">
                                                        <tr class="item-item">
                                                            <td class="text-center align-middle"><strong>1.</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm item-title"
                                                                       placeholder="e.g., Exposure & Mobilization of The Long Saphenous Vein Harvesting" required>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check">
                                                                    <input class="form-check-input table-checkbox" type="checkbox">
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check">
                                                                    <input class="form-check-input table-checkbox" type="checkbox">
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-outline-primary btn-sm add-item">
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION C: SCIENTIFIC MEETING BY MATCVS -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">C. SCIENTIFIC MEETING BY MALAYSIAN ASSOCIATION THORACIC AND CARDIOVASCULAR SURGERY (MATCVS)</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSubSection('sectionC')">
                                        <i class="fa fa-plus"></i> Add Meeting
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSection('sectionC')">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card section-content" id="sectionCContent">
                                <div class="card-body">
                                    <div class="sub-sections-container" id="sectionCSubSections">
                                        <!-- Default Sub-Section -->
                                        <div class="sub-section-item mb-4">
                                            <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2 fw-bold">Attended MATCVS Annual Scientific Meeting</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleSubSection(this)">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" title="Move Up" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" title="Move Down" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sub-section-body">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Title</th>
                                                            <th width="100">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="items-container">
                                                        <tr class="item-item">
                                                            <td class="text-center align-middle"><strong>1.</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm item-title"
                                                                       placeholder="Meeting title or details" required>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input item-checkbox" type="checkbox">
                                                                    <label class="form-check-label">✓</label>
                                                                </div>
                                                                <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-outline-primary btn-sm add-item">
                                                    <i class="fa fa-plus"></i> Add Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION D: COMPULSORY COURSES -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">D. COMPULSORY COURSES</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSubSection('sectionD')">
                                        <i class="fa fa-plus"></i> Add Course Section
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSection('sectionD')">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card section-content" id="sectionDContent">
                                <div class="card-body">
                                    <div class="sub-sections-container" id="sectionDSubSections">
                                        <!-- Default Sub-Section -->
                                        <div class="sub-section-item mb-4">
                                            <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2 fw-bold">Required Courses</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleSubSection(this)">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" title="Move Up" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" title="Move Down" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sub-section-body">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Course Title</th>
                                                            <th width="100">Completed</th>
                                                            <th width="120">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="items-container">
                                                        <tr class="item-item">
                                                            <td class="text-center align-middle"><strong>1.</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm item-title mb-1"
                                                                       placeholder="e.g., Basic Surgical Skill Education Training (BSSET)" required>
                                                                <div>
                                                                    <small class="text-muted">Alternative:</small>
                                                                    <input type="text" class="form-control form-control-sm alternative-text"
                                                                           placeholder="e.g., Or Advanced Trauma Life support (ATLS)">
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input item-checkbox" type="checkbox">
                                                                    <label class="form-check-label">✓</label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-outline-primary btn-sm add-item">
                                                    <i class="fa fa-plus"></i> Add Course
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- SECTION E: RESEARCH -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0 fw-bold" style="color: #2c3e50;">E. RESEARCH</h4>
                                <div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSubSection('sectionE')">
                                        <i class="fa fa-plus"></i> Add Research Section
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleSection('sectionE')">
                                        <i class="fa fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card section-content" id="sectionEContent">
                                <div class="card-body">
                                    <div class="sub-sections-container" id="sectionESubSections">
                                        <!-- Default Sub-Section -->
                                        <div class="sub-section-item mb-4">
                                            <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mb-0 me-2 fw-bold">Research Projects</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleSubSection(this)">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" title="Move Up" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" title="Move Down" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sub-section-body">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Research Title & Description</th>
                                                            <th width="100">Completed</th>
                                                            <th width="120">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="items-container">
                                                        <tr class="item-item">
                                                            <td class="text-center align-middle"><strong>1.</strong></td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm item-title mb-1"
                                                                       placeholder="Research Title" required>
                                                                <textarea class="form-control form-control-sm item-description"
                                                                          placeholder="Research Description" rows="2"></textarea>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input item-checkbox" type="checkbox">
                                                                    <label class="form-check-label">✓</label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" disabled>
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-outline-primary btn-sm add-item">
                                                    <i class="fa fa-plus"></i> Add Research Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Status *</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Form</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let masterModal;
    let subSectionCounters = {
        sectionA: 0,
        sectionB: 0,
        sectionC: 0,
        sectionD: 0,
        sectionE: 0
    };
    let itemCounters = {};

    // Initialize DataTable
    $('#requirementsTable').DataTable({
        paging: false,
        info: false,
        searching: true,
        ordering: true
    });

    // Initialize modal
    masterModal = new bootstrap.Modal(document.getElementById('masterModal'));

    // Open Create Modal
    window.openCreateModal = function() {
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#modalTitle').text('LONGITUDINAL REQUIREMENT FORM');
        $('#status').val('active');

        // Reset all sections
        resetAllSections();

        masterModal.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id) {
        $.ajax({
            url: `longitudinal-requirements/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#record_id').val(response.id);
                $('#title').val(response.title);
                $('#course_title').val(response.course_title);
                $('#status').val(response.status);
                $('#modalTitle').text('Edit LONGITUDINAL REQUIREMENT FORM');

                // Reset all sections
                resetAllSections();

                // Load sections data
                if (response.sections && response.sections.length > 0) {
                    response.sections.forEach(function(section, sectionIndex) {
                        const sectionLetter = section.section_letter;
                        const containerId = `section${sectionLetter}SubSections`;

                        // Clear existing sub-sections
                        $(`#${containerId}`).html('');

                        // Load sub-sections
                        if (section.sub_sections && section.sub_sections.length > 0) {
                            section.sub_sections.forEach(function(subSection, subSectionIndex) {
                                addSubSectionFromData(containerId, sectionLetter, subSection);
                            });
                        } else {
                            // Add one default sub-section if none exist
                            addDefaultSubSection(containerId, sectionLetter);
                        }
                    });
                }

                masterModal.show();
            },
            error: function() {
                toastr.error('Failed to load form data');
            }
        });
    };

    // Reset All Sections
    function resetAllSections() {
        // Reset all sub-section containers
        $('.sub-sections-container').each(function() {
            const container = $(this);
            const containerId = container.attr('id');
            const sectionLetter = containerId.replace('section', '').replace('SubSections', '')[0];

            // Clear container
            container.html('');

            // Add one default sub-section
            addDefaultSubSection(containerId, sectionLetter);
        });

        // Reset counters
        subSectionCounters = {
            sectionA: 0,
            sectionB: 0,
            sectionC: 0,
            sectionD: 0,
            sectionE: 0
        };
        itemCounters = {};
    }

    // Add Default Sub-Section
    function addDefaultSubSection(containerId, sectionLetter) {
        const sectionType = getSectionType(sectionLetter);
        let subSectionHtml = '';

        switch(sectionType) {
            case 'A':
                subSectionHtml = getSectionATemplate(containerId, 0);
                break;
            case 'B':
                subSectionHtml = getSectionBTemplate(containerId, 0);
                break;
            case 'C':
                subSectionHtml = getSectionCTemplate(containerId, 0);
                break;
            case 'D':
                subSectionHtml = getSectionDTemplate(containerId, 0);
                break;
            case 'E':
                subSectionHtml = getSectionETemplate(containerId, 0);
                break;
        }

        $(`#${containerId}`).append(subSectionHtml);
        subSectionCounters[containerId] = 1;
        itemCounters[containerId] = {};
        itemCounters[containerId][0] = 1;

        // Add event listeners
        addSubSectionEventListeners($(`#${containerId} .sub-section-item`).last(), containerId);
    }

    // Get Section Type
    function getSectionType(sectionLetter) {
        return sectionLetter; // A, B, C, D, E
    }

    // Add Sub-Section from Data (for edit)
    function addSubSectionFromData(containerId, sectionLetter, subSectionData) {
        const sectionType = getSectionType(sectionLetter);
        let subSectionHtml = '';
        const subSectionIndex = subSectionCounters[containerId] || 0;

        switch(sectionType) {
            case 'A':
                subSectionHtml = getSectionATemplate(containerId, subSectionIndex, subSectionData);
                break;
            case 'B':
                subSectionHtml = getSectionBTemplate(containerId, subSectionIndex, subSectionData);
                break;
            case 'C':
                subSectionHtml = getSectionCTemplate(containerId, subSectionIndex, subSectionData);
                break;
            case 'D':
                subSectionHtml = getSectionDTemplate(containerId, subSectionIndex, subSectionData);
                break;
            case 'E':
                subSectionHtml = getSectionETemplate(containerId, subSectionIndex, subSectionData);
                break;
        }

        $(`#${containerId}`).append(subSectionHtml);
        subSectionCounters[containerId] = (subSectionCounters[containerId] || 0) + 1;

        // Add event listeners
        addSubSectionEventListeners($(`#${containerId} .sub-section-item`).last(), containerId);
    }

    // Get Section A Template
    function getSectionATemplate(containerId, index, data = null) {
        return `
            <div class="sub-section-item mb-4" data-index="${index}">
                <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm w-auto sub-section-title"
                               value="${data ? data.sub_section_title : 'CARDIOLOGY ROTATION'}"
                               placeholder="Sub Section Title" required>
                        <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="toggleSubSection(this)">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="sub-section-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Title</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="items-container">
                            ${data && data.items ? getSectionAItemsHtml(data.items) : getDefaultSectionAItemHtml()}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm add-item">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                </div>
            </div>
        `;
    }

    // Get Section B Template
    function getSectionBTemplate(containerId, index, data = null) {
        const tableColumns = data && data.table_columns ? data.table_columns : ['A/B', 'C/D'];
        return `
            <div class="sub-section-item mb-4" data-index="${index}">
                <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm w-auto sub-section-title"
                               value="${data ? data.sub_section_title : 'Cardiothoracic Surgery Rotation'}"
                               placeholder="Sub Section Title" required>
                        <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="toggleSubSection(this)">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="sub-section-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Table Columns:</label>
                        <div class="row g-2">
                            ${tableColumns.map((col, i) => `
                                <div class="col-md-3">
                                    <input type="text" class="form-control form-control-sm table-column"
                                           value="${col}" placeholder="Column ${i + 1}">
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Title</th>
                                ${tableColumns.map(col => `
                                    <th class="text-center" width="80">${col}</th>
                                `).join('')}
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="items-container">
                            ${data && data.items ? getSectionBItemsHtml(data.items, tableColumns) : getDefaultSectionBItemHtml(tableColumns)}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm add-item">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                </div>
            </div>
        `;
    }

    // Get Section C Template
    function getSectionCTemplate(containerId, index, data = null) {
        return `
            <div class="sub-section-item mb-4" data-index="${index}">
                <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm w-auto sub-section-title"
                               value="${data ? data.sub_section_title : 'Attended MATCVS Annual Scientific Meeting'}"
                               placeholder="Meeting Title" required>
                        <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="toggleSubSection(this)">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="sub-section-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Title</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="items-container">
                            ${data && data.items ? getSectionAItemsHtml(data.items) : getDefaultSectionAItemHtml()}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm add-item">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                </div>
            </div>
        `;
    }

    // Get Section D Template
    function getSectionDTemplate(containerId, index, data = null) {
        return `
            <div class="sub-section-item mb-4" data-index="${index}">
                <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm w-auto sub-section-title"
                               value="${data ? data.sub_section_title : 'Required Courses'}"
                               placeholder="Course Section Title" required>
                        <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="toggleSubSection(this)">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="sub-section-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Course Title</th>
                                <th width="100">Completed</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody class="items-container">
                            ${data && data.items ? getSectionDItemsHtml(data.items) : getDefaultSectionDItemHtml()}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm add-item">
                        <i class="fa fa-plus"></i> Add Course
                    </button>
                </div>
            </div>
        `;
    }

    // Get Section E Template
    function getSectionETemplate(containerId, index, data = null) {
        return `
            <div class="sub-section-item mb-4" data-index="${index}">
                <div class="sub-section-header d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control form-control-sm w-auto sub-section-title"
                               value="${data ? data.sub_section_title : 'Research Projects'}"
                               placeholder="Research Section Title" required>
                        <button type="button" class="btn btn-sm btn-outline-info ms-2" onclick="toggleSubSection(this)">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-section" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="sub-section-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Research Title & Description</th>
                                <th width="100">Completed</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody class="items-container">
                            ${data && data.items ? getSectionEItemsHtml(data.items) : getDefaultSectionEItemHtml()}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm add-item">
                        <i class="fa fa-plus"></i> Add Research Item
                    </button>
                </div>
            </div>
        `;
    }

    // Get Section A Items HTML
    function getSectionAItemsHtml(items) {
        return items.map((item, i) => `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>${item.item_number}</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title"
                           value="${item.item_title}" required>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox" ${item.is_checked ? 'checked' : ''}>
                        <label class="form-check-label">✓</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Get Section B Items HTML
    function getSectionBItemsHtml(items, columns) {
        return items.map((item, i) => {
            const tableValues = item.table_values || {};
            return `
                <tr class="item-item">
                    <td class="text-center align-middle"><strong>${item.item_number}</strong></td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-title"
                               value="${item.item_title}" required>
                    </td>
                    ${columns.map((col, j) => `
                        <td class="text-center align-middle">
                            <div class="form-check">
                                <input class="form-check-input table-checkbox" type="checkbox"
                                       ${tableValues[col] ? 'checked' : ''}>
                            </div>
                        </td>
                    `).join('')}
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Get Section D Items HTML
    function getSectionDItemsHtml(items) {
        return items.map((item, i) => `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>${item.item_number}</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title mb-1"
                           value="${item.item_title}" required>
                    <div>
                        <small class="text-muted">Alternative:</small>
                        <input type="text" class="form-control form-control-sm alternative-text"
                               value="${item.alternative_text || ''}"
                               placeholder="e.g., Or Advanced Trauma Life support (ATLS)">
                    </div>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox" ${item.is_checked ? 'checked' : ''}>
                        <label class="form-check-label">✓</label>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Get Section E Items HTML
    function getSectionEItemsHtml(items) {
        return items.map((item, i) => `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>${item.item_number}</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title mb-1"
                           value="${item.item_title}" required>
                    <textarea class="form-control form-control-sm item-description"
                              placeholder="Research Description" rows="2">${item.description || ''}</textarea>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox" ${item.is_checked ? 'checked' : ''}>
                        <label class="form-check-label">✓</label>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Get Default Section A Item HTML
    function getDefaultSectionAItemHtml() {
        return `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>1.</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title"
                           placeholder="e.g., Transthoracic Echocardiography (TTE)" required>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox">
                        <label class="form-check-label">✓</label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Get Default Section B Item HTML
    function getDefaultSectionBItemHtml(columns) {
        return `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>1.</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title"
                           placeholder="e.g., Exposure & Mobilization of The Long Saphenous Vein Harvesting" required>
                </td>
                ${columns.map(col => `
                    <td class="text-center align-middle">
                        <div class="form-check">
                            <input class="form-check-input table-checkbox" type="checkbox">
                        </div>
                    </td>
                `).join('')}
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Get Default Section D Item HTML
    function getDefaultSectionDItemHtml() {
        return `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>1.</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title mb-1"
                           placeholder="e.g., Basic Surgical Skill Education Training (BSSET)" required>
                    <div>
                        <small class="text-muted">Alternative:</small>
                        <input type="text" class="form-control form-control-sm alternative-text"
                               placeholder="e.g., Or Advanced Trauma Life support (ATLS)">
                    </div>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox">
                        <label class="form-check-label">✓</label>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Get Default Section E Item HTML
    function getDefaultSectionEItemHtml() {
        return `
            <tr class="item-item">
                <td class="text-center align-middle"><strong>1.</strong></td>
                <td>
                    <input type="text" class="form-control form-control-sm item-title mb-1"
                           placeholder="Research Title" required>
                    <textarea class="form-control form-control-sm item-description"
                              placeholder="Research Description" rows="2"></textarea>
                </td>
                <td class="text-center align-middle">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input item-checkbox" type="checkbox">
                        <label class="form-check-label">✓</label>
                    </div>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-danger btn-sm btn-icon remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Toggle Section (expand/collapse)
    window.toggleSection = function(sectionId) {
        const sectionContent = $(`#${sectionId}Content`);
        const toggleBtn = $(`#${sectionId}Content`).closest('.row').find('.section-header button:last-child');

        if (sectionContent.is(':visible')) {
            sectionContent.slideUp(300);
            toggleBtn.html('<i class="fa fa-chevron-down"></i>');
        } else {
            sectionContent.slideDown(300);
            toggleBtn.html('<i class="fa fa-chevron-up"></i>');
        }
    };

    // Toggle Sub-Section
    window.toggleSubSection = function(button) {
        const subSectionBody = $(button).closest('.sub-section-header').siblings('.sub-section-body');

        if (subSectionBody.is(':visible')) {
            subSectionBody.slideUp(300);
            $(button).html('<i class="fa fa-chevron-down"></i>');
        } else {
            subSectionBody.slideDown(300);
            $(button).html('<i class="fa fa-chevron-up"></i>');
        }
    };

    // Add Sub-Section Event Listeners
    function addSubSectionEventListeners(subSectionItem, containerId) {
        const subSectionIndex = $(subSectionItem).data('index');

        if (!itemCounters[containerId]) {
            itemCounters[containerId] = {};
        }
        if (!itemCounters[containerId][subSectionIndex]) {
            itemCounters[containerId][subSectionIndex] = 1;
        }

        // Move up button
        $(subSectionItem).find('.move-up').on('click', function() {
            const currentItem = $(this).closest('.sub-section-item');
            const prevItem = currentItem.prev('.sub-section-item');

            if (prevItem.length) {
                currentItem.insertBefore(prevItem);
                updateSubSectionNumbers(containerId);
                updateSubSectionButtons(containerId);
            }
        });

        // Move down button
        $(subSectionItem).find('.move-down').on('click', function() {
            const currentItem = $(this).closest('.sub-section-item');
            const nextItem = currentItem.next('.sub-section-item');

            if (nextItem.length) {
                currentItem.insertAfter(nextItem);
                updateSubSectionNumbers(containerId);
                updateSubSectionButtons(containerId);
            }
        });

        // Remove sub-section
        $(subSectionItem).find('.remove-sub-section').on('click', function() {
            const subSections = $(`#${containerId} .sub-section-item`);
            if (subSections.length > 1) {
                $(this).closest('.sub-section-item').remove();
                updateSubSectionNumbers(containerId);
                updateSubSectionButtons(containerId);
                updateRemoveButtons(containerId);
            } else {
                toastr.warning('At least one sub-section is required');
            }
        });

        // Add item button
        $(subSectionItem).find('.add-item').on('click', function() {
            const itemsContainer = $(this).siblings('table').find('tbody');
            const sectionLetter = containerId.replace('section', '').replace('SubSections', '')[0];
            const itemIndex = itemsContainer.find('.item-item').length;

            let newItemHtml = '';
            switch(sectionLetter) {
                case 'A':
                case 'C':
                    newItemHtml = getDefaultSectionAItemHtml();
                    break;
                case 'B':
                    const columns = $(subSectionItem).find('.table-column').map(function() {
                        return $(this).val();
                    }).get();
                    newItemHtml = getDefaultSectionBItemHtml(columns);
                    break;
                case 'D':
                    newItemHtml = getDefaultSectionDItemHtml();
                    break;
                case 'E':
                    newItemHtml = getDefaultSectionEItemHtml();
                    break;
            }

            itemsContainer.append(newItemHtml);
            updateItemNumbers(itemsContainer);
            updateRemoveButtons(containerId);

            // Add remove item event listener
            $(itemsContainer).find('.item-item').last().find('.remove-item').on('click', function() {
                if ($(itemsContainer).find('.item-item').length > 1) {
                    $(this).closest('.item-item').remove();
                    updateItemNumbers(itemsContainer);
                    updateRemoveButtons(containerId);
                } else {
                    toastr.warning('At least one item is required');
                }
            });

            itemCounters[containerId][subSectionIndex]++;
        });

        // Remove item event listeners for existing items
        $(subSectionItem).find('.remove-item').on('click', function() {
            const itemsContainer = $(this).closest('tbody');
            if ($(itemsContainer).find('.item-item').length > 1) {
                $(this).closest('.item-item').remove();
                updateItemNumbers(itemsContainer);
                updateRemoveButtons(containerId);
            } else {
                toastr.warning('At least one item is required');
            }
        });

        // Sub-section title change to input field
        $(subSectionItem).find('.sub-section-title').on('click', function() {
            if ($(this).is('input')) return;

            const currentText = $(this).text();
            const input = $('<input>', {
                type: 'text',
                class: 'form-control form-control-sm w-auto sub-section-title-input',
                value: currentText
            });

            $(this).replaceWith(input);
            input.focus();

            input.on('blur', function() {
                const newText = $(this).val();
                const span = $('<span>', {
                    class: 'sub-section-title',
                    text: newText
                });
                $(this).replaceWith(span);
            });
        });
    }

    // Update Sub-Section Numbers
    function updateSubSectionNumbers(containerId) {
        $(`#${containerId} .sub-section-item`).each(function(index) {
            $(this).data('index', index);
        });
    }

    // Update Sub-Section Buttons
    function updateSubSectionButtons(containerId) {
        const subSections = $(`#${containerId} .sub-section-item`);

        subSections.each(function(index) {
            const $moveUp = $(this).find('.move-up');
            const $moveDown = $(this).find('.move-down');
            const $removeBtn = $(this).find('.remove-sub-section');

            // Enable/disable move up button
            if (index === 0) {
                $moveUp.prop('disabled', true);
            } else {
                $moveUp.prop('disabled', false);
            }

            // Enable/disable move down button
            if (index === subSections.length - 1) {
                $moveDown.prop('disabled', true);
            } else {
                $moveDown.prop('disabled', false);
            }

            // Enable/disable remove button
            if (subSections.length === 1) {
                $removeBtn.prop('disabled', true);
            } else {
                $removeBtn.prop('disabled', false);
            }
        });
    }

    // Update Remove Buttons
    function updateRemoveButtons(containerId) {
        $(`#${containerId} tbody`).each(function() {
            const items = $(this).find('.item-item');
            if (items.length === 1) {
                $(this).find('.remove-item').prop('disabled', true);
            } else {
                $(this).find('.remove-item').prop('disabled', false);
            }
        });
    }

    // Update Item Numbers
    function updateItemNumbers(itemsContainer) {
        $(itemsContainer).find('.item-item').each(function(index) {
            $(this).find('td:first strong').text(`${index + 1}.`);
        });
    }

    // Add Sub-Section
    window.addSubSection = function(sectionId) {
        const containerId = `${sectionId}SubSections`;
        const sectionLetter = sectionId.replace('section', '');
        const subSectionIndex = subSectionCounters[containerId] || 0;

        let newSubSectionHtml = '';
        switch(sectionLetter) {
            case 'A':
                newSubSectionHtml = getSectionATemplate(containerId, subSectionIndex);
                break;
            case 'B':
                newSubSectionHtml = getSectionBTemplate(containerId, subSectionIndex);
                break;
            case 'C':
                newSubSectionHtml = getSectionCTemplate(containerId, subSectionIndex);
                break;
            case 'D':
                newSubSectionHtml = getSectionDTemplate(containerId, subSectionIndex);
                break;
            case 'E':
                newSubSectionHtml = getSectionETemplate(containerId, subSectionIndex);
                break;
        }

        $(`#${containerId}`).append(newSubSectionHtml);
        subSectionCounters[containerId] = (subSectionCounters[containerId] || 0) + 1;
        itemCounters[containerId] = itemCounters[containerId] || {};
        itemCounters[containerId][subSectionIndex] = 1;

        // Add event listeners
        addSubSectionEventListeners($(`#${containerId} .sub-section-item`).last(), containerId);
        updateSubSectionNumbers(containerId);
        updateSubSectionButtons(containerId);
        updateRemoveButtons(containerId);
    };

    // Submit Form
    $('#masterForm').on('submit', function(e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `longitudinal-requirements/${id}` : `longitudinal-requirements`;
        let method = id ? 'PUT' : 'POST';

        // Prepare sections data
        let sections = [];

        // Process each section (A, B, C, D, E)
        ['A', 'B', 'C', 'D', 'E'].forEach((sectionLetter, sectionIndex) => {
            const sectionContainer = $(`#section${sectionLetter}SubSections`);

            const sectionData = {
                section_letter: sectionLetter,
                section_title: getSectionTitle(sectionLetter),
                sub_sections: []
            };

            // Process each sub-section
            sectionContainer.find('.sub-section-item').each(function(subSectionIndex) {
                const subSectionItem = $(this);
                const subSectionData = {
                    sub_section_title: subSectionItem.find('.sub-section-title').val() || subSectionItem.find('.sub-section-title-input').val(),
                    sub_section_type: sectionLetter,
                    items: [],
                    table_columns: []
                };

                // Add table columns for section B
                if (sectionLetter === 'B') {
                    const columns = subSectionItem.find('.table-column').map(function() {
                        return $(this).val();
                    }).get();
                    subSectionData.table_columns = columns;
                }

                // Process each item
                subSectionItem.find('.item-item').each(function(itemIndex) {
                    const itemElement = $(this);
                    const itemData = {
                        item_number: `${itemIndex + 1}.`,
                        item_title: itemElement.find('.item-title').val(),
                        is_checked: itemElement.find('.item-checkbox').is(':checked') || itemElement.find('.table-checkbox').first().is(':checked'),
                        alternative_text: null,
                        description: null,
                        table_values: {}
                    };

                    // Add alternative text for section D
                    if (sectionLetter === 'D') {
                        itemData.alternative_text = itemElement.find('.alternative-text').val();
                    }

                    // Add description for section E
                    if (sectionLetter === 'E') {
                        itemData.description = itemElement.find('.item-description').val();
                    }

                    // Add table values for section B
                    if (sectionLetter === 'B') {
                        const columns = subSectionData.table_columns;
                        columns.forEach((col, colIndex) => {
                            itemData.table_values[col] = itemElement.find('.table-checkbox').eq(colIndex).is(':checked');
                        });
                    }

                    subSectionData.items.push(itemData);
                });

                sectionData.sub_sections.push(subSectionData);
            });

            sections.push(sectionData);
        });

        // Prepare form data
        let formData = {
            _token: '{{ csrf_token() }}',
            _method: method,
            title: $('#title').val(),
            course_title: $('#course_title').val(),
            status: $('#status').val(),
            sections: sections
        };

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    masterModal.hide();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });

    // Get Section Title
    function getSectionTitle(sectionLetter) {
        const sectionTitles = {
            'A': 'HOSPITAL ATTACHMENT ACTIVITIES',
            'B': 'SUMMARY TABLE OF OPERATION THEATRE/PROCEDURE REQUIREMENT',
            'C': 'SCIENTIFIC MEETING BY MALAYSIAN ASSOCIATION THORACIC AND CARDIOVASCULAR SURGERY (MATCVS)',
            'D': 'COMPULSORY COURSES',
            'E': 'RESEARCH'
        };
        return sectionTitles[sectionLetter];
    }

    // Delete Record
    window.deleteRecord = function(id) {
        if (!confirm('Are you sure you want to delete this requirement form?')) return;

        $.ajax({
            url: `longitudinal-requirements/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function() {
                toastr.error('Something went wrong!');
            }
        });
    };

    // Initialize all sections as expanded by default
    setTimeout(() => {
        $('.section-content').show();
        $('.sub-section-body').show();
    }, 100);
});
</script>

<style>
.modal-title {
    font-weight: 700;
    font-size: 1.3rem;
}

hr {
    border-color: #dee2e6;
    margin: 1.5rem 0;
}

.section-header {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px 8px 0 0;
    border: 1px solid #dee2e6;
    border-bottom: none;
}

.section-header h4 {
    color: #2c3e50;
    margin: 0;
    font-size: 1.1rem;
}

.section-content {
    border: 1px solid #dee2e6;
    border-radius: 0 0 8px 8px;
    margin-bottom: 2rem;
}

.sub-section-item {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background-color: #fdfdfd;
    margin-bottom: 1.5rem;
}

.sub-section-header {
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.sub-section-header h6 {
    margin: 0;
    font-size: 0.95rem;
}

.table {
    margin-bottom: 1rem;
    font-size: 0.85rem;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border: 1px solid #dee2e6;
    padding: 0.5rem;
}

.table td {
    vertical-align: middle;
    border: 1px solid #dee2e6;
    padding: 0.5rem;
}

.table input[type="text"],
.table textarea {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    font-size: 0.85rem;
}

.table input[type="text"]:focus,
.table textarea:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-group .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    margin-left: 2px;
}

.btn-icon {
    padding: 0.25rem 0.5rem;
    width: 32px;
}

.form-check-input {
    cursor: pointer;
    margin-top: 0;
}

.move-up:disabled,
.move-down:disabled,
.remove-sub-section:disabled,
.remove-item:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-outline-primary {
    border-color: #3498db;
    color: #3498db;
}

.btn-outline-primary:hover {
    background-color: #3498db;
    color: white;
}

.text-muted {
    font-size: 0.8rem;
    color: #6c757d !important;
}

.fw-bold {
    font-weight: 600 !important;
}

.sub-section-title {
    font-weight: 600;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid transparent;
}

.sub-section-title:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.sub-section-title-input {
    width: 250px !important;
}

.modal-body {
    scrollbar-width: thin;
    scrollbar-color: #adb5bd #f8f9fa;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.modal-body::-webkit-scrollbar-thumb {
    background-color: #adb5bd;
    border-radius: 20px;
}
</style>
@endpush
