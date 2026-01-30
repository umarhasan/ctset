{{-- resources/views/admin/rotation-evaluations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Rotation Evaluation Forms</h3>
        <div class="ms-auto">
            @can('rotation-evaluations-create')
                <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                    <i class="fa fa-plus"></i> Add Rotation Evaluation Forms
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <table id="examTable" class="table table-bordered table-hover table-striped mb-0 text-center w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Course Title</th>
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->id }}</td>
                    <td>{{ $evaluation->title }}</td>
                    <td>{{ $evaluation->course_title }}</td>
                    <td>
                        <span class="badge bg-info">{{ $evaluation->sections->count() }} sections</span>
                    </td>
                    <td>
                        @if($evaluation->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $evaluation->created_at->format('Y-m-d') }}</td>
                    <td>
                        @can('rotation-evaluations-update')
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

<!-- MODAL -->
<div class="modal fade" id="masterModal">
    <div class="modal-dialog modal-xl">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="modal-title text-center w-100">ROTATION EVALUATION FORM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Title and Course Title -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="text-center mb-3" style="color: #2c3e50;">ROTATION EVALUATION FORM</h4>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Title:</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg"
                                   placeholder="Enter form title" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Course Title:</label>
                            <input type="text" name="course_title" id="course_title" class="form-control"
                                   placeholder="e.g., MASTER IN SURGICAL SCIENCES M.SURG.SC (CT)" required>
                        </div>
                    </div>

                    <!-- Form Points Sections -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-list"></i> Form Points</h5>
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

<!-- Template for Section -->
<template id="sectionTemplate">
    <div class="section-item card mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center w-100">
                <span class="section-letter me-2 fw-bold" style="font-size: 1.2rem;">A.</span>
                <input type="text" class="form-control form-control-sm section-title"
                       placeholder="Section Title (e.g., PATIENT RESPONSIBILITIES)" required>
                <select class="form-control form-control-sm ms-2 section-type" style="width: 150px;">
                    <option value="text">Text Input</option>
                    <option value="yes_no">Yes/No with Comment</option>
                    <option value="scale_5">5-Point Scale</option>
                    <option value="scale_5_with_desc">5-Point Scale with Descriptions</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-info btn-sm move-up" title="Move Up">
                    <i class="fa fa-arrow-up"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm move-down" title="Move Down">
                    <i class="fa fa-arrow-down"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm remove-section" title="Remove Section">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Section Description -->
            <div class="mb-3">
                <textarea class="form-control form-control-sm section-description"
                          placeholder="Section description (optional)" rows="2"></textarea>
            </div>

            <!-- Subitems Container -->
            <div class="subitems-container mb-3">
                <div class="subitem-item mb-2">
                    <div class="d-flex align-items-center">
                        <span class="me-2 fw-bold">1.</span>
                        <input type="text" class="form-control form-control-sm subitem-text"
                               placeholder="Subitem text (e.g., ESTIMATED PATIENT NUMBERS)">
                        <select class="form-control form-control-sm ms-2 subitem-type" style="width: 120px;">
                            <option value="text">Text Input</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                        </select>
                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-subitem" disabled>
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Subitem Button -->
            <button type="button" class="btn btn-outline-primary btn-sm add-subitem">
                <i class="fa fa-plus"></i> Add Subitem
            </button>

            <!-- Scale Options (for scale_5_with_desc type) -->
            <div class="scale-options mt-3" style="display: none;">
                <h6 class="mb-2">Scale Descriptions:</h6>
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <label>Option 1:</label>
                        <input type="text" class="form-control form-control-sm scale-option"
                               placeholder="Description for 1">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Option 2:</label>
                        <input type="text" class="form-control form-control-sm scale-option"
                               placeholder="Description for 2">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Option 3:</label>
                        <input type="text" class="form-control form-control-sm scale-option"
                               placeholder="Description for 3">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Option 4:</label>
                        <input type="text" class="form-control form-control-sm scale-option"
                               placeholder="Description for 4">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Option 5:</label>
                        <input type="text" class="form-control form-control-sm scale-option"
                               placeholder="Description for 5">
                    </div>
                </div>
            </div>

            <!-- Scale Table Preview -->
            <div class="scale-table-preview mt-3" style="display: none;">
                <h6 class="mb-2">Scale Preview:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">1</th>
                                <th class="text-center">2</th>
                                <th class="text-center">3</th>
                                <th class="text-center">4</th>
                                <th class="text-center">5</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><i class="fas fa-circle text-muted"></i></td>
                                <td class="text-center"><i class="fas fa-circle text-muted"></i></td>
                                <td class="text-center"><i class="fas fa-circle text-muted"></i></td>
                                <td class="text-center"><i class="fas fa-circle text-muted"></i></td>
                                <td class="text-center"><i class="fas fa-circle text-muted"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let sectionCount = 0;
    let masterModal;

    // Initialize DataTable
    $('#evaluationsTable').DataTable({
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
        $('#modalTitle').text('ROTATION EVALUATION FORM');
        $('#status').val('active');

        // Reset sections container
        $('#sectionsContainer').html('');
        sectionCount = 0;

        masterModal.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id) {
        $.ajax({
            url: `rotation-evaluations/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#record_id').val(response.id);
                $('#title').val(response.title);
                $('#course_title').val(response.course_title);
                $('#status').val(response.status);
                $('#modalTitle').text('Edit ROTATION EVALUATION FORM');

                // Clear and load sections
                $('#sectionsContainer').html('');
                if (response.sections && response.sections.length > 0) {
                    response.sections.forEach(function(section, index) {
                        addSectionFromData(section, index);
                    });
                    sectionCount = response.sections.length;
                }

                masterModal.show();
            },
            error: function() {
                toastr.error('Failed to load form data');
            }
        });
    };

    // Add Section from Data (for edit)
    function addSectionFromData(sectionData, index) {
        const template = document.getElementById('sectionTemplate').content.cloneNode(true);
        const sectionItem = template.querySelector('.section-item');

        // Set section data
        const letter = String.fromCharCode(65 + index); // A, B, C, ...
        sectionItem.querySelector('.section-letter').textContent = letter + '.';
        sectionItem.querySelector('.section-title').value = sectionData.section_title || '';
        sectionItem.querySelector('.section-type').value = sectionData.section_type || 'text';
        sectionItem.querySelector('.section-description').value = sectionData.description || '';

        // Set scale options if exist
        if (sectionData.options && sectionData.section_type === 'scale_5_with_desc') {
            const scaleOptions = sectionData.options;
            sectionItem.querySelectorAll('.scale-option').forEach((input, i) => {
                if (scaleOptions[i]) {
                    input.value = scaleOptions[i];
                }
            });
            sectionItem.querySelector('.scale-options').style.display = 'block';
            sectionItem.querySelector('.scale-table-preview').style.display = 'block';
        }

        // Clear default subitem and add from data
        const subitemsContainer = sectionItem.querySelector('.subitems-container');
        subitemsContainer.innerHTML = '';

        if (sectionData.subitems && sectionData.subitems.length > 0) {
            sectionData.subitems.forEach(function(subitem, subIndex) {
                addSubitemToSection(sectionItem, subitem.subitem_text, subitem.input_type, subIndex);
            });
        } else {
            // Add one default subitem
            addSubitemToSection(sectionItem, '', 'text', 0);
        }

        // Add event listeners
        addSectionEventListeners(sectionItem);

        $('#sectionsContainer').append(sectionItem);
        updateSectionButtons();
        updateSubitemNumbers();
    }

    // Add New Section
    window.addSection = function() {
        const template = document.getElementById('sectionTemplate').content.cloneNode(true);
        const sectionItem = template.querySelector('.section-item');

        // Set section letter
        const letter = String.fromCharCode(65 + sectionCount); // A, B, C, ...
        sectionItem.querySelector('.section-letter').textContent = letter + '.';

        // Add event listeners
        addSectionEventListeners(sectionItem);

        $('#sectionsContainer').append(sectionItem);
        sectionCount++;
        updateSectionButtons();
        updateSubitemNumbers();
    };

    // Add Section Event Listeners
    function addSectionEventListeners(sectionItem) {
        // Section type change
        $(sectionItem).find('.section-type').on('change', function() {
            const type = $(this).val();
            const scaleOptions = $(sectionItem).find('.scale-options');
            const scalePreview = $(sectionItem).find('.scale-table-preview');

            if (type === 'scale_5_with_desc') {
                scaleOptions.show();
                scalePreview.show();
            } else {
                scaleOptions.hide();
                scalePreview.hide();
            }
        });

        // Move section up
        $(sectionItem).find('.move-up').on('click', function() {
            const currentItem = $(this).closest('.section-item');
            const prevItem = currentItem.prev('.section-item');

            if (prevItem.length) {
                currentItem.insertBefore(prevItem);
                updateSectionLetters();
                updateSectionButtons();
            }
        });

        // Move section down
        $(sectionItem).find('.move-down').on('click', function() {
            const currentItem = $(this).closest('.section-item');
            const nextItem = currentItem.next('.section-item');

            if (nextItem.length) {
                currentItem.insertAfter(nextItem);
                updateSectionLetters();
                updateSectionButtons();
            }
        });

        // Remove section
        $(sectionItem).find('.remove-section').on('click', function() {
            if ($('#sectionsContainer .section-item').length > 1) {
                $(this).closest('.section-item').remove();
                updateSectionLetters();
                updateSectionButtons();
                sectionCount--;
            } else {
                toastr.warning('At least one section is required');
            }
        });

        // Add subitem
        $(sectionItem).find('.add-subitem').on('click', function() {
            const subitemsContainer = $(this).siblings('.subitems-container');
            const subitemCount = subitemsContainer.find('.subitem-item').length;
            addSubitemToContainer(subitemsContainer[0], '', 'text', subitemCount);
            updateSubitemNumbers();
            updateSubitemButtons();
        });
    }

    // Add Subitem to Section
    function addSubitemToSection(sectionItem, text, type, index) {
        const subitemsContainer = sectionItem.querySelector('.subitems-container');
        addSubitemToContainer(subitemsContainer, text, type, index);
    }

    // Add Subitem to Container
    function addSubitemToContainer(container, text, type, index) {
        const subitemDiv = document.createElement('div');
        subitemDiv.className = 'subitem-item mb-2';
        subitemDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <span class="me-2 fw-bold subitem-number">${index + 1}.</span>
                <input type="text" class="form-control form-control-sm subitem-text"
                       placeholder="Subitem text" value="${text}">
                <select class="form-control form-control-sm ms-2 subitem-type" style="width: 120px;">
                    <option value="text" ${type === 'text' ? 'selected' : ''}>Text Input</option>
                    <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                    <option value="radio" ${type === 'radio' ? 'selected' : ''}>Radio</option>
                </select>
                <button type="button" class="btn btn-danger btn-sm ms-2 remove-subitem">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        `;

        // Add remove event listener
        $(subitemDiv).find('.remove-subitem').on('click', function() {
            if ($(container).find('.subitem-item').length > 1) {
                $(this).closest('.subitem-item').remove();
                updateSubitemNumbers();
                updateSubitemButtons();
            } else {
                toastr.warning('At least one subitem is required');
            }
        });

        container.appendChild(subitemDiv);
    }

    // Update Section Letters
    function updateSectionLetters() {
        $('#sectionsContainer .section-item').each(function(index) {
            const letter = String.fromCharCode(65 + index);
            $(this).find('.section-letter').textContent = letter + '.';
        });
    }

    // Update Subitem Numbers
    function updateSubitemNumbers() {
        $('#sectionsContainer .section-item').each(function() {
            $(this).find('.subitem-item').each(function(index) {
                $(this).find('.subitem-number').text(`${index + 1}.`);
            });
        });
    }

    // Update Section Buttons State
    function updateSectionButtons() {
        const sections = $('#sectionsContainer .section-item');

        sections.each(function(index) {
            const $this = $(this);
            const $moveUp = $this.find('.move-up');
            const $moveDown = $this.find('.move-down');

            $moveUp.prop('disabled', index === 0);
            $moveDown.prop('disabled', index === sections.length - 1);
        });
    }

    // Update Subitem Buttons State
    function updateSubitemButtons() {
        $('.subitems-container').each(function() {
            const subitems = $(this).find('.subitem-item');
            if (subitems.length === 1) {
                $(this).find('.remove-subitem').prop('disabled', true);
            } else {
                $(this).find('.remove-subitem').prop('disabled', false);
            }
        });
    }

    // Submit Form
    $('#masterForm').on('submit', function(e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `rotation-evaluations/${id}` : `rotation-evaluations`;
        let method = id ? 'PUT' : 'POST';

        // Prepare sections data
        let sections = [];
        $('#sectionsContainer .section-item').each(function(index) {
            const $this = $(this);
            const sectionData = {
                section_title: $this.find('.section-title').val(),
                section_type: $this.find('.section-type').val(),
                description: $this.find('.section-description').val(),
                order: index,
                subitems: [],
                options: []
            };

            // Collect scale options for scale_5_with_desc type
            if (sectionData.section_type === 'scale_5_with_desc') {
                $this.find('.scale-option').each(function() {
                    sectionData.options.push($(this).val());
                });
            }

            // Collect subitems
            $this.find('.subitem-item').each(function(subIndex) {
                sectionData.subitems.push({
                    subitem_text: $(this).find('.subitem-text').val(),
                    input_type: $(this).find('.subitem-type').val(),
                    order: subIndex
                });
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

    // Delete Record
    window.deleteRecord = function(id) {
        if (!confirm('Are you sure you want to delete this evaluation form?')) return;

        $.ajax({
            url: `rotation-evaluations/${id}`,
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

    // Add one default section on create
    $('#masterModal').on('shown.bs.modal', function() {
        if ($('#sectionsContainer').is(':empty')) {
            window.addSection();
        }
    });
});
</script>

<style>
.modal-title {
    font-weight: 700;
    font-size: 1.3rem;
}

.section-item {
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.section-letter {
    color: #2c3e50;
    min-width: 25px;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
}

.btn-group .btn-sm {
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
}

.scale-options .form-control-sm {
    font-size: 0.75rem;
    padding: 0.2rem 0.4rem;
}

.subitem-item {
    padding: 8px;
    background-color: #f9f9f9;
    border-radius: 4px;
    border-left: 3px solid #3498db;
}

.subitem-number {
    min-width: 25px;
}

.table.table-sm th, .table.table-sm td {
    padding: 0.3rem;
    text-align: center;
}

.fa-circle.text-muted {
    color: #95a5a6;
    font-size: 0.9rem;
}

.add-subitem {
    font-size: 0.8rem;
    padding: 0.2rem 0.6rem;
}
</style>
@endpush
