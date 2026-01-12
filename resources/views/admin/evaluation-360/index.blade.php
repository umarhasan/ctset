@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">360 Evaluation Forms</h3>
        <div class="ms-auto">
            <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Create Form
            </button>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover" id="evaluationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
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
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $evaluation->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $evaluation->id }})">
                            <i class="fa fa-trash"></i>
                        </button>
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
    <div class="modal-dialog modal-lg">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="modal-title text-center w-100">Create 360 EVALUATION FORM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Title:</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" placeholder="Enter form title" required>
                        </div>
                    </div>

                    <!-- Form Points Header -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold">Form Points</h6>
                                <button type="button" class="btn btn-success btn-sm" onclick="addSection()">
                                    <i class="fa fa-plus"></i> Add Section
                                </button>
                            </div>

                            <!-- Table Header -->
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 45%">Title</th>
                                            <th style="width: 15%">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody id="sectionsContainer">
                                        <!-- Sections will be added here -->
                                        <tr class="section-item">
                                            <td>
                                                <input type="text" name="sections[0][section_title]"
                                                       class="form-control form-control-sm"
                                                       placeholder="Section title" required>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm move-up" onclick="moveSectionUp(this)" disabled>
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-sm move-down" onclick="moveSectionDown(this)" disabled>
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSection(this)" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-circle text-muted"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let sectionCount = 1;
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
        $('#modalTitle').text('Create 360 EVALUATION FORM');
        $('#status').val('active');

        // Reset sections container with one empty section
        $('#sectionsContainer').html(`
            <tr class="section-item">
                <td>
                    <input type="text" name="sections[0][section_title]"
                           class="form-control form-control-sm"
                           placeholder="Section title" required>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" onclick="moveSectionUp(this)" disabled>
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" onclick="moveSectionDown(this)" disabled>
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeSection(this)" disabled>
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>

            </tr>
        `);

        sectionCount = 1;
        updateSectionButtons();
        masterModal.show();
    };

    // Open Edit Modal
    window.openEditModal = function(id) {
        $.ajax({
            url: `evaluation-360/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#record_id').val(response.id);
                $('#title').val(response.title);
                $('#status').val(response.status);
                $('#modalTitle').text('Edit 360 EVALUATION FORM');

                // Clear and load sections
                let sectionsHtml = '';
                if (response.sections && response.sections.length > 0) {
                    response.sections.forEach(function(section, index) {
                        sectionsHtml += `
                            <tr class="section-item">
                                <td>
                                    <input type="text" name="sections[${index}][section_title]"
                                           class="form-control form-control-sm"
                                           placeholder="Section title"
                                           value="${section.section_title}" required>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm move-up" onclick="moveSectionUp(this)">
                                            <i class="fa fa-arrow-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm move-down" onclick="moveSectionDown(this)">
                                            <i class="fa fa-arrow-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeSection(this)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        `;
                    });
                    sectionCount = response.sections.length;
                } else {
                    sectionsHtml = `
                        <tr class="section-item">
                            <td>
                                <input type="text" name="sections[0][section_title]"
                                       class="form-control form-control-sm"
                                       placeholder="Section title" required>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm move-up" onclick="moveSectionUp(this)" disabled>
                                        <i class="fa fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm move-down" onclick="moveSectionDown(this)" disabled>
                                        <i class="fa fa-arrow-down"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSection(this)" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    `;
                    sectionCount = 1;
                }

                $('#sectionsContainer').html(sectionsHtml);
                updateSectionButtons();
                masterModal.show();
            },
            error: function() {
                toastr.error('Failed to load form data');
            }
        });
    };

    // Add New Section
    window.addSection = function() {
        let newSection = `
            <tr class="section-item">
                <td>
                    <input type="text" name="sections[${sectionCount}][section_title]"
                           class="form-control form-control-sm"
                           placeholder="Section title" required>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm move-up" onclick="moveSectionUp(this)">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm move-down" onclick="moveSectionDown(this)">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeSection(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>

            </tr>
        `;

        $('#sectionsContainer').append(newSection);
        sectionCount++;
        updateSectionButtons();
    };

    // Remove Section
    window.removeSection = function(button) {
        $(button).closest('.section-item').remove();
        reindexSections();
        updateSectionButtons();
    };

    // Move Section Up
    window.moveSectionUp = function(button) {
        let currentItem = $(button).closest('.section-item');
        let prevItem = currentItem.prev('.section-item');

        if (prevItem.length) {
            currentItem.insertBefore(prevItem);
            reindexSections();
            updateSectionButtons();
        }
    };

    // Move Section Down
    window.moveSectionDown = function(button) {
        let currentItem = $(button).closest('.section-item');
        let nextItem = currentItem.next('.section-item');

        if (nextItem.length) {
            currentItem.insertAfter(nextItem);
            reindexSections();
            updateSectionButtons();
        }
    };

    // Reindex Sections
    function reindexSections() {
        sectionCount = 0;
        $('#sectionsContainer .section-item').each(function(index) {
            $(this).find('input').attr('name', `sections[${index}][section_title]`);
            sectionCount++;
        });
    }

    // Update Section Buttons State
    function updateSectionButtons() {
        let sections = $('#sectionsContainer .section-item');

        sections.each(function(index) {
            let $this = $(this);
            let $moveUp = $this.find('.move-up');
            let $moveDown = $this.find('.move-down');
            let $removeBtn = $this.find('.btn-danger');

            // Enable/disable move up button
            if (index === 0) {
                $moveUp.prop('disabled', true).addClass('disabled');
            } else {
                $moveUp.prop('disabled', false).removeClass('disabled');
            }

            // Enable/disable move down button
            if (index === sections.length - 1) {
                $moveDown.prop('disabled', true).addClass('disabled');
            } else {
                $moveDown.prop('disabled', false).removeClass('disabled');
            }

            // Enable/disable remove button
            if (sections.length === 1) {
                $removeBtn.prop('disabled', true).addClass('disabled');
            } else {
                $removeBtn.prop('disabled', false).removeClass('disabled');
            }
        });
    }

    // Submit Form
    $('#masterForm').on('submit', function(e) {
        e.preventDefault();

        let id = $('#record_id').val();
        let url = id ? `evaluation-360/${id}` : `evaluation-360`;
        let method = id ? 'PUT' : 'POST';

        // Prepare sections data
        let sections = [];
        $('#sectionsContainer .section-item').each(function() {
            let title = $(this).find('input').val();
            if (title.trim() !== '') {
                sections.push({
                    section_title: title
                });
            }
        });

        // Prepare form data
        let formData = {
            _token: '{{ csrf_token() }}',
            _method: method,
            title: $('#title').val(),
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
            url: `evaluation-360/${id}`,
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

    // Initialize section buttons on load
    updateSectionButtons();
});
</script>

<style>
.modal-title {
    font-weight: 700;
    font-size: 1.2rem;
}

.form-control-lg {
    font-size: 1.1rem;
    padding: 0.75rem 1rem;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    padding: 0.5rem;
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.section-item:hover {
    background-color: #f8f9fa;
}

.btn-group .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

.btn-info:hover:not(.disabled) {
    background-color: #138496;
    border-color: #117a8b;
}

.btn-info.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-danger.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.fa-circle {
    font-size: 0.8rem;
}

.text-muted {
    color: #6c757d !important;
}
</style>
@endpush
