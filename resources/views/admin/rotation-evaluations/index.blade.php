@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Rotation Evaluation Forms</h3>
        <div class="ms-auto">
            @can('rotation-evaluations-create')
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Add Rotation Evaluation Form
            </button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Course Title</th>
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->id }}</td>
                    <td>{{ $evaluation->title }}</td>
                    <td>{{ $evaluation->course_title }}</td>
                    <td>
                        <span class="badge bg-info">{{ count($evaluation->sections ?? []) }} sections</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $evaluation->status=='active'?'success':'danger' }}">{{ ucfirst($evaluation->status) }}</span>
                    </td>
                    <td>{{ $evaluation->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('rotation-evaluations.show', $evaluation->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i>
                        </a>
                        @can('rotation-evaluations-edit')
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $evaluation->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        @endcan
                        @can('rotation-evaluations-delete')
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $evaluation->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $evaluations->links() }}
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="masterModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="modal-title w-100 text-center">ROTATION EVALUATION FORM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Title & Course -->
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Title:</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Course Title:</label>
                            <input type="text" name="course_title" id="course_title" class="form-control" required>
                        </div>
                    </div>

                    <!-- Sections -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Form Sections</h5>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addSection()">
                                        <i class="fa fa-plus"></i> Add Section
                                    </button>
                                </div>
                                <div class="card-body" id="sectionsContainer">
                                    <!-- Sections will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Status:</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Form</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Section Template -->
<template id="sectionTemplate">
    <div class="section-item card mb-3">
        <div class="card-header d-flex align-items-center">
            <span class="section-letter fw-bold me-2">A.</span>
            <input type="text" class="form-control form-control-sm section-title" placeholder="Section Title" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-section"><i class="fa fa-trash"></i></button>
        </div>

        <div class="card-body">
            <textarea class="form-control section-description mb-2" placeholder="Section Description (optional)" rows="2"></textarea>

            <!-- Subitems -->
            <div class="subitems-container mb-2">
                <div class="subitem-item mb-2">
                    <div class="d-flex align-items-center">
                        <span class="subitem-number fw-bold me-2">1.</span>
                        <input type="text" class="form-control form-control-sm subitem-text" placeholder="Subitem Text">
                        <select class="form-control form-control-sm ms-2 subitem-type" style="width: 180px;">
                            <option value="text">Text Input</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                            <option value="scale_5">5 Scale</option>
                            <option value="scale_5_with_desc">5-Point Scale with Description</option>
                        </select>
                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-subitem"><i class="fa fa-trash"></i></button>
                    </div>

                    <!-- 5-Point Scale with Description -->
                    <div class="scale-desc-container mt-2" style="display:none;">
                        <div class="row">
                            @for($i=1;$i<=5;$i++)
                            <div class="col-md-2 mb-2">
                                <label>Value {{$i}}:</label>
                                <input type="text" class="form-control form-control-sm scale-desc" placeholder="Description {{$i}}">
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm add-subitem"><i class="fa fa-plus"></i> Add Subitem</button>
        </div>
    </div>
</template>

<!-- Empty Subitem Template -->
<template id="subitemTemplate">
    <div class="subitem-item mb-2">
        <div class="d-flex align-items-center">
            <span class="subitem-number fw-bold me-2">1.</span>
            <input type="text" class="form-control form-control-sm subitem-text" placeholder="Subitem Text">
            <select class="form-control form-control-sm ms-2 subitem-type" style="width: 180px;">
                <option value="text">Text Input</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radio</option>
                <option value="scale_5">5 Scale</option>
                <option value="scale_5_with_desc">5-Point Scale with Description</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-subitem"><i class="fa fa-trash"></i></button>
        </div>

        <!-- 5-Point Scale with Description -->
        <div class="scale-desc-container mt-2" style="display:none;">
            <div class="row">
                @for($i=1;$i<=5;$i++)
                <div class="col-md-2 mb-2">
                    <label>Value {{$i}}:</label>
                    <input type="text" class="form-control form-control-sm scale-desc" placeholder="Description {{$i}}">
                </div>
                @endfor
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    const masterModal = new bootstrap.Modal('#masterModal');

    // Open Create Modal
    window.openCreateModal = function(){
        $('#record_id').val('');
        $('#masterForm')[0].reset();
        $('#sectionsContainer').html('');
        addSection(); // Add first empty section
        $('#modalTitle').text('CREATE ROTATION EVALUATION FORM');
        masterModal.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id){
        $('#record_id').val(id);
        $('#sectionsContainer').html('');
        $('#modalTitle').text('EDIT ROTATION EVALUATION FORM');
        
        // Show loading state
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: `/rotation-evaluations/${id}/edit`,
            method: 'GET',
            success: function(response) {
                Swal.close();
                
                // Fill basic details
                $('#title').val(response.title);
                $('#course_title').val(response.course_title);
                $('#status').val(response.status);
                
                // Load sections
                if(response.sections && response.sections.length > 0) {
                    response.sections.forEach(function(section, sectionIndex) {
                        addSection(); // Add section container
                        
                        // Get the last added section
                        const lastSection = $('#sectionsContainer .section-item').last();
                        
                        // Fill section data
                        lastSection.find('.section-title').val(section.section_title);
                        lastSection.find('.section-description').val(section.description || '');
                        
                        // Clear default subitems
                        const subitemsContainer = lastSection.find('.subitems-container');
                        subitemsContainer.empty();
                        
                        // Add subitems
                        if(section.subitems && section.subitems.length > 0) {
                            section.subitems.forEach(function(subitem, subitemIndex) {
                                addSubitemToContainer(subitemsContainer, subitem, subitemIndex);
                            });
                        } else {
                            // Add at least one empty subitem
                            addSubitemToContainer(subitemsContainer, null, 0);
                        }
                    });
                } else {
                    // If no sections, add one empty section with one subitem
                    addSection();
                }
                
                // Update all numbers
                updateSectionLetters();
                updateSubitemNumbers();
                addSubitemEvents();
                
                masterModal.show();
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error loading evaluation data: ' + (xhr.responseJSON?.message || 'Unknown error')
                });
                console.error(xhr);
            }
        });
    };

    // Helper function to add subitem to container
    function addSubitemToContainer(container, subitemData = null, index = 0) {
        const template = $('#subitemTemplate').html();
        const $newSubitem = $(template);
        
        // Update number
        $newSubitem.find('.subitem-number').text((index + 1) + '.');
        
        if (subitemData) {
            // Fill subitem data
            $newSubitem.find('.subitem-text').val(subitemData.subitem_text || '');
            $newSubitem.find('.subitem-type').val(subitemData.input_type || 'text');
            
            // Handle scale descriptions
            if (subitemData.input_type === 'scale_5_with_desc' && subitemData.scale_desc) {
                $newSubitem.find('.scale-desc-container').show();
                subitemData.scale_desc.forEach(function(desc, i) {
                    $newSubitem.find('.scale-desc').eq(i).val(desc || '');
                });
            }
        }
        
        container.append($newSubitem);
    }

    // Add Section
    window.addSection = function(){
        const template = $('#sectionTemplate').html();
        $('#sectionsContainer').append(template);
        updateSectionLetters();
        addSectionEventListeners();
        
        // Ensure each new section has at least one subitem
        const lastSection = $('#sectionsContainer .section-item').last();
        const subitemsContainer = lastSection.find('.subitems-container');
        if (subitemsContainer.children().length === 0) {
            addSubitemToContainer(subitemsContainer, null, 0);
        }
    };

    // Add section event listeners
    function addSectionEventListeners(){
        // Remove section
        $('.remove-section').off('click').on('click', function(){
            if($('.section-item').length > 1){
                $(this).closest('.section-item').remove();
                updateSectionLetters();
                updateSubitemNumbers();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one section is required'
                });
            }
        });

        // Add subitem
        $('.add-subitem').off('click').on('click', function(){
            const container = $(this).closest('.section-item').find('.subitems-container');
            const currentCount = container.find('.subitem-item').length;
            addSubitemToContainer(container, null, currentCount);
            updateSubitemNumbers();
            addSubitemEvents();
        });

        addSubitemEvents();
    }

    // Add subitem event listeners
    function addSubitemEvents(){
        // Subitem type change
        $('.subitem-type').off('change').on('change', function(){
            const item = $(this).closest('.subitem-item');
            const selectedType = $(this).val();
            
            if(selectedType === 'scale_5_with_desc'){
                item.find('.scale-desc-container').slideDown();
            } else {
                item.find('.scale-desc-container').slideUp();
            }
        });

        // Remove subitem
        $('.remove-subitem').off('click').on('click', function(){
            const container = $(this).closest('.subitems-container');
            if(container.find('.subitem-item').length > 1){
                $(this).closest('.subitem-item').remove();
                updateSubitemNumbers();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one subitem is required per section'
                });
            }
        });
    }

    // Update section letters (A, B, C, etc.)
    function updateSectionLetters(){
        $('.section-item').each(function(index){
            const letter = String.fromCharCode(65 + index); // 65 is ASCII for 'A'
            $(this).find('.section-letter').text(letter + '.');
        });
    }

    // Update subitem numbers (1, 2, 3, etc.) across all sections
    function updateSubitemNumbers(){
        let globalCounter = 1;
        $('.subitem-item').each(function(){
            $(this).find('.subitem-number').text(globalCounter + '.');
            globalCounter++;
        });
    }

    // Form Submit
    $('#masterForm').on('submit', function(e){
        e.preventDefault();
        
        let id = $('#record_id').val();
        let url = id ? `/rotation-evaluations/${id}` : '/rotation-evaluations';
        let method = id ? 'PUT' : 'POST';
        
        // Validate sections
        if ($('#sectionsContainer .section-item').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'At least one section is required'
            });
            return;
        }
        
        // Build sections data
        let sections = [];
        let isValid = true;
        
        $('#sectionsContainer .section-item').each(function(sectionIndex){
            let sectionTitle = $(this).find('.section-title').val().trim();
            if (!sectionTitle) {
                isValid = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: `Section ${String.fromCharCode(65 + sectionIndex)} title is required`
                });
                return false;
            }
            
            let subitems = [];
            $(this).find('.subitem-item').each(function(){
                let subitemText = $(this).find('.subitem-text').val().trim();
                if (!subitemText) {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validation Error',
                        text: 'All subitem texts are required'
                    });
                    return false;
                }
                
                let scale_desc = [];
                if ($(this).find('.subitem-type').val() === 'scale_5_with_desc') {
                    $(this).find('.scale-desc').each(function(){
                        scale_desc.push($(this).val() || '');
                    });
                }
                
                subitems.push({
                    subitem_text: subitemText,
                    input_type: $(this).find('.subitem-type').val(),
                    scale_desc: scale_desc
                });
            });
            
            if (!isValid) return false;
            
            sections.push({
                section_title: sectionTitle,
                description: $(this).find('.section-description').val(),
                section_type: 'text',
                subitems: subitems
            });
        });
        
        if (!isValid) return;
        
        // Show loading
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit data
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: method,
                title: $('#title').val().trim(),
                course_title: $('#course_title').val().trim(),
                status: $('#status').val(),
                sections: sections
            },
            success: function(response){
                Swal.close();
                if(response.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Form saved successfully',
                        timer: 1500
                    }).then(() => {
                        masterModal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong'
                    });
                }
            },
            error: function(xhr){
                Swal.close();
                let errorMessage = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
                console.error(xhr);
            }
        });
    });

    // Delete Record
    window.deleteRecord = function(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/rotation-evaluations/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response){
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Record deleted successfully',
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Something went wrong'
                            });
                        }
                    },
                    error: function(xhr){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record'
                        });
                    }
                });
            }
        });
    };

    // Initialize on page load
    // Add first section when modal opens for create
    $('#masterModal').on('shown.bs.modal', function() {
        if ($('#record_id').val() === '' && $('#sectionsContainer .section-item').length === 0) {
            addSection();
        }
    });

    // Reset form when modal is hidden
    $('#masterModal').on('hidden.bs.modal', function() {
        $('#masterForm')[0].reset();
        $('#sectionsContainer').html('');
        $('#record_id').val('');
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush