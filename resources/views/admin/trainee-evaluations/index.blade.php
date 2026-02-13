@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Trainee Evaluation Forms</h3>
        <div class="ms-auto">
            @can('trainee-evaluations-create')
                <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                    <i class="fa fa-plus"></i> Add Trainee Evaluation Forms
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
                        @can('trainee-evaluations-edit')
                            <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $evaluation->id }})">
                                <i class="fa fa-edit"></i>
                            </button>
                        @endcan
                        <a href="{{ route('trainee-evaluations.show',$evaluation->id) }}">view</a>
                        @can('trainee-evaluations-delete')
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
<div class="modal fade" id="masterModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="modal-title">Create Trainee Evaluation Form</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label>Form Title *</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                    </div>

                    <!-- Form Points Sections with Points -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Form Sections & Points</h6>
                                    
                                    <div class="ms-auto">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addMainSection()">
                                        <i class="fa fa-plus"></i> Add Main Section
                                    </button>
                                    </div>
                                    
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
                            <label>Status *</label>
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
        let mainSectionCount = 0;
        let masterModal;

        // Default sections with their points from the image
        const defaultSections = [
            {
                title: 'CLINICAL KNOWLEDGE/MEDICAL EXPERTISE: ACQUISITION AND APPLICATION',
                points: [
                    'Knowledge of basic sciences',
                    'Able to recognize & differentiate specific cardiothoracic surgical issues'
                ]
            },
            
        ];

        // Initialize modal
        masterModal = new bootstrap.Modal(document.getElementById('masterModal'));

        // Open Create Modal
        window.openCreateModal = function() {
            $('#record_id').val('');
            $('#masterForm')[0].reset();
            $('#modalTitle').text('Create Trainee Evaluation Form');
            $('#status').val('active');

            // Load default sections with points
            loadDefaultSections();

            masterModal.show();
        };

        // Function to load default sections with points
        function loadDefaultSections() {
            let sectionsHtml = '';
            
            defaultSections.forEach(function(section, sectionIndex) {
                sectionsHtml += generateMainSectionHtml(sectionIndex, section.title, section.points);
            });

            $('#sectionsContainer').html(sectionsHtml);
            mainSectionCount = defaultSections.length;
            updateMainSectionButtons();
        }

        // Generate HTML for main section with points (WITHOUT move up/down buttons)
        function generateMainSectionHtml(sectionIndex, title = '', points = []) {
            let pointsHtml = '';
            
            if (points.length > 0) {
                points.forEach(function(point, pointIndex) {
                    pointsHtml += generatePointHtml(sectionIndex, pointIndex, point);
                });
            } else {
                pointsHtml += generatePointHtml(sectionIndex, 0, '');
            }

            return `
                <div class="main-section-item card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center w-75">
                            <input type="text" 
                                name="sections[${sectionIndex}][title]" 
                                class="form-control form-control-sm main-section-title" 
                                placeholder="Main Section Title" 
                                value="${title}" required>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm remove-section" 
                                    onclick="removeMainSection(this)" ${defaultSections.length === 1 ? 'disabled' : ''}>
                                <i class="fa fa-trash"></i> Delete Section
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="points-container mb-2" data-section="${sectionIndex}">
                            ${pointsHtml}
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addPoint(this, ${sectionIndex})">
                            <i class="fa fa-plus"></i> Add Point
                        </button>
                    </div>
                </div>
            `;
        }

        // Generate HTML for point (WITHOUT move up/down buttons)
        function generatePointHtml(sectionIndex, pointIndex, value = '') {
            return `
                <div class="point-item row mb-2 align-items-center">
                    <div class="col-md-10">
                        <input type="text" 
                            name="sections[${sectionIndex}][points][${pointIndex}]" 
                            class="form-control form-control-sm point-input" 
                            placeholder="Enter evaluation point" 
                            value="${value}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-point" 
                                onclick="removePoint(this)">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `;
        }

        // Add new main section
        window.addMainSection = function() {
            let newSectionHtml = generateMainSectionHtml(mainSectionCount, '', ['']);
            $('#sectionsContainer').append(newSectionHtml);
            mainSectionCount++;
            reindexMainSections();
            updateMainSectionButtons();
        };

        // Add new point to section
        window.addPoint = function(button, sectionIndex) {
            let pointsContainer = $(button).closest('.main-section-item').find('.points-container');
            let pointCount = pointsContainer.find('.point-item').length;
            let newPointHtml = generatePointHtml(sectionIndex, pointCount, '');
            pointsContainer.append(newPointHtml);
            reindexPoints(pointsContainer);
        };

        // Remove main section
        window.removeMainSection = function(button) {
            if ($('#sectionsContainer .main-section-item').length > 1) {
                $(button).closest('.main-section-item').remove();
                reindexMainSections();
                updateMainSectionButtons();
            }
        };

        // Remove point
        window.removePoint = function(button) {
            let pointsContainer = $(button).closest('.points-container');
            let pointsCount = pointsContainer.find('.point-item').length;
            
            if (pointsCount > 1) {
                $(button).closest('.point-item').remove();
                reindexPoints(pointsContainer);
            } else {
                toastr.warning('At least one point is required per section');
            }
        };

        // Reindex main sections
        function reindexMainSections() {
            $('#sectionsContainer .main-section-item').each(function(sectionIndex) {
                // Update section title name
                $(this).find('.main-section-title').attr('name', `sections[${sectionIndex}][title]`);
                
                // Update points container data-section
                let pointsContainer = $(this).find('.points-container');
                pointsContainer.attr('data-section', sectionIndex);
                
                // Update point names
                reindexPoints(pointsContainer);
                
                // Update add point button onclick
                $(this).find('.btn-primary').attr('onclick', `addPoint(this, ${sectionIndex})`);
            });
            mainSectionCount = $('#sectionsContainer .main-section-item').length;
        }

        // Reindex points in a section
        function reindexPoints(pointsContainer) {
            let sectionIndex = pointsContainer.attr('data-section');
            pointsContainer.find('.point-item').each(function(pointIndex) {
                $(this).find('.point-input').attr('name', `sections[${sectionIndex}][points][${pointIndex}]`);
            });
        }

        // Update main section buttons
        function updateMainSectionButtons() {
            let sections = $('#sectionsContainer .main-section-item');
            let totalSections = sections.length;

            sections.each(function(index) {
                let $this = $(this);
                let $removeBtn = $this.find('.remove-section');

                // Enable/disable remove button
                $removeBtn.prop('disabled', totalSections === 1);
            });
        }

        // Open Edit Modal
        window.openEditModal = function(id) {
            $.ajax({
                url: `trainee-evaluations/${id}/edit`,
                method: 'GET',
                success: function(response) {
                    $('#record_id').val(response.id);
                    $('#title').val(response.title);
                    $('#status').val(response.status);
                    $('#modalTitle').text('Edit Trainee Evaluation Form');

                    // Load sections from response
                    if (response.sections && response.sections.length > 0) {
                        let sectionsHtml = '';
                        response.sections.forEach(function(section, sectionIndex) {
                            let points = section.points ? section.points.map(p => p.point_text) : [];
                            sectionsHtml += generateMainSectionHtml(sectionIndex, section.section_title, points);
                        });
                        $('#sectionsContainer').html(sectionsHtml);
                        mainSectionCount = response.sections.length;
                    } else {
                        loadDefaultSections();
                    }

                    updateMainSectionButtons();
                    masterModal.show();
                },
                error: function() {
                    toastr.error('Failed to load form data');
                }
            });
        };

        // Submit Form
        $('#masterForm').on('submit', function(e) {
            e.preventDefault();

            let id = $('#record_id').val();
            let url = id ? `trainee-evaluations/${id}` : `trainee-evaluations`;
            let method = id ? 'PUT' : 'POST';

            // Prepare sections data with points
            let sections = [];
            $('#sectionsContainer .main-section-item').each(function(sectionIndex) {
                let sectionTitle = $(this).find('.main-section-title').val().trim();
                if (sectionTitle !== '') {
                    let points = [];
                    $(this).find('.point-input').each(function() {
                        let pointValue = $(this).val().trim();
                        if (pointValue !== '') {
                            points.push(pointValue);
                        }
                    });
                    
                    if (points.length > 0) {
                        sections.push({
                            title: sectionTitle,
                            points: points
                        });
                    }
                }
            });

            if (sections.length === 0) {
                toastr.error('At least one section with points is required');
                return;
            }

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
                url: `trainee-evaluations/${id}`,
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
    });
</script>
@endpush

<style>
    .main-section-item {
        border: 2px solid #dee2e6;
        margin-bottom: 20px;
    }
    
    .main-section-item .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .point-item {
        padding: 8px;
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    
    .point-item:hover {
        background-color: #f1f3f5;
    }
    
    .points-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    
    .btn-group {
        gap: 2px;
    }
    
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
    
    .btn-group .btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    .modal-xl {
        max-width: 90%;
    }
    
    .main-section-title {
        font-weight: 600;
        background-color: #fff;
    }
    
    .point-input {
        background-color: #fff;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .remove-section, .remove-point {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .remove-section:hover, .remove-point:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }
    
    .remove-section:disabled, .remove-point:disabled {
        background-color: #e35d6a;
        border-color: #e35d6a;
        opacity: 0.5;
    }
</style>