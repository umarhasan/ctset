@extends('layouts.app')

@section('title', 'Edit CV: ' . $cv->title)

@section('content')

<style>
    .cv-edit-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .section-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .section-card:hover {
        transform: translateY(-2px);
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0d6efd;
    }
    
    .entry-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #0d6efd;
    }
    
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .sidebar-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .completeness-meter {
        text-align: center;
    }
    
    .completeness-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#0d6efd 0% {{ $completeness ?? 0 }}%, #e9ecef {{ $completeness ?? 0 }}% 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .completeness-circle-inner {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .preview-mini {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        background: white;
        margin-top: 20px;
    }
    
    .template-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }
    
    .template-option {
        border: 2px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .template-option:hover {
        border-color: #0d6efd;
    }
    
    .template-option.selected {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #dee2e6;
    }
</style>

<div class="cv-edit-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Basic Information</h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                data-bs-toggle="modal" data-bs-target="#editBasicInfoModal">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">CV Title</label>
                                <p class="fs-5">{{ $cv->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Primary Speciality</label>
                                <p class="fs-5">{{ $cv->primary_speciality ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Professional Summary</label>
                                <p>{{ $cv->summary ?? 'No summary added' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Template</label>
                                <p>{{ $cv->template == 'template1' ? 'Modern Professional' : 'Academic Focus' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Visibility</label>
                                <p>
                                    @if($cv->is_public)
                                        <span class="badge bg-success">Public</span>
                                    @else
                                        <span class="badge bg-secondary">Private</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Information -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Profile Information</h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                    </div>
                    
                    @if($cv->profile)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">1212Full Name</label>
                                    <p>{{ $cv->user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">University</label>
                                    <p>{{ $cv->profile->university }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Class Year</label>
                                    <p>{{ $cv->profile->class_year }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Primary Interest</label>
                                    <p>{{ $cv->profile->primary_interest }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email12</label>
                                    <p>{{ $cv->user->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p>{{ $cv->profile->phone }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-circle"></i>
                            <h5>No Profile Information</h5>
                            <p class="mb-3">Add your profile details to make your CV complete</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-plus me-1"></i>Add Profile
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Education -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Education</h4>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#addEducationModal">
                            <i class="fas fa-plus me-1"></i>Add Education
                        </button>
                    </div>
                    
                    @forelse($cv->educations as $edu)
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>{{ $edu->title }}</h6>
                                    <p class="mb-1 text-muted">{{ $edu->institute }}</p>
                                    <small class="text-muted">
                                        {{ $edu->year_from }} - {{ $edu->year_to ?? 'Present' }}
                                    </small>
                                    @if($edu->details)
                                        <p class="mt-2 mb-0">{{ $edu->details }}</p>
                                    @endif
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary edit-education"
                                            data-id="{{ $edu->id }}"
                                            data-title="{{ $edu->title }}"
                                            data-institute="{{ $edu->institute }}"
                                            data-year_from="{{ $edu->year_from }}"
                                            data-year_to="{{ $edu->year_to }}"
                                            data-details="{{ $edu->details }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('education.destroy', $edu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this education entry?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-graduation-cap"></i>
                            <h5>No Education Added</h5>
                            <p class="mb-3">Add your education details</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#addEducationModal">
                                <i class="fas fa-plus me-1"></i>Add Education
                            </button>
                        </div>
                    @endforelse
                </div>
                
                <!-- Clinical Experience -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Clinical Experience</h4>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#addClinicalModal">
                            <i class="fas fa-plus me-1"></i>Add Clinical
                        </button>
                    </div>
                    
                    @forelse($cv->clinicals as $clinical)
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>{{ $clinical->title }}</h6>
                                    <p class="mb-1 text-muted">{{ $clinical->institute }}</p>
                                    <small class="text-muted">
                                        {{ $clinical->year_from }} - {{ $clinical->year_to ?? 'Present' }}
                                    </small>
                                    @if($clinical->details)
                                        <p class="mt-2 mb-0">{{ $clinical->details }}</p>
                                    @endif
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary edit-clinical"
                                            data-id="{{ $clinical->id }}"
                                            data-title="{{ $clinical->title }}"
                                            data-institute="{{ $clinical->institute }}"
                                            data-year_from="{{ $clinical->year_from }}"
                                            data-year_to="{{ $clinical->year_to }}"
                                            data-details="{{ $clinical->details }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('education.destroy', $clinical->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this clinical entry?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-stethoscope"></i>
                            <h5>No Clinical Experience</h5>
                            <p class="mb-3">Add your clinical rotations and experience</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#addClinicalModal">
                                <i class="fas fa-plus me-1"></i>Add Clinical
                            </button>
                        </div>
                    @endforelse
                </div>
                
                <!-- Research -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Research & Publications</h4>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#addResearchModal">
                            <i class="fas fa-plus me-1"></i>Add Research
                        </button>
                    </div>
                    
                    @forelse($cv->researches as $research)
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>{{ $research->title }}</h6>
                                    <p class="mb-1 text-muted">Year: {{ $research->year }}</p>
                                    @if($research->details)
                                        <p class="mt-2 mb-0">{{ $research->details }}</p>
                                    @endif
                                </div>
                                <div class="action-buttons">
                                    <form action="{{ route('research.destroy', $research->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this research entry?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-flask"></i>
                            <h5>No Research Added</h5>
                            <p class="mb-3">Add your research projects and publications</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#addResearchModal">
                                <i class="fas fa-plus me-1"></i>Add Research
                            </button>
                        </div>
                    @endforelse
                </div>
                
                <!-- Awards -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Awards & Honors</h4>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#addAwardModal">
                            <i class="fas fa-plus me-1"></i>Add Award
                        </button>
                    </div>
                    
                    @forelse($cv->awards as $award)
                        <div class="entry-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6>{{ $award->title }}</h6>
                                    <p class="mb-1 text-muted">Year: {{ $award->year }}</p>
                                    @if($award->details)
                                        <p class="mt-2 mb-0">{{ $award->details }}</p>
                                    @endif
                                </div>
                                <div class="action-buttons">
                                    <form action="{{ route('research.destroy', $award->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this award entry?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-award"></i>
                            <h5>No Awards Added</h5>
                            <p class="mb-3">Add your awards, honors, and recognitions</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#addAwardModal">
                                <i class="fas fa-plus me-1"></i>Add Award
                            </button>
                        </div>
                    @endforelse
                </div>
                
                <!-- Documents -->
                <div class="section-card">
                    <div class="section-header">
                        <h4>Documents</h4>
                        <button type="button" class="btn btn-sm btn-primary" 
                                data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                            <i class="fas fa-plus me-1"></i>Upload Document
                        </button>
                    </div>
                    
                    @forelse($cv->documents as $document)
                        <div class="entry-item">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6>{{ $document->title }}</h6>

            @php
                $fileExt = pathinfo($document->file, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($fileExt), ['jpg','jpeg','png']);
            @endphp

            @if($isImage)
                <a href="{{ route('cv.document.stream', $document->file) }}" target="_blank">
                    <img src="{{ route('cv.document.stream', $document->file) }}" 
                         alt="{{ $document->title }}" 
                         style="max-height:80px; border-radius:5px;">
                </a>
            @else
                <p class="mb-0 text-muted">
                    <a href="{{ route('cv.document.stream', $document->file) }}" target="_blank">
                        {{ $document->file }}
                    </a>
                </p>
            @endif
        </div>

        <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary edit-document"
                    data-id="{{ $document->id }}"
                    data-title="{{ $document->title }}"
                    data-file="{{ $document->file }}">
                <i class="fas fa-edit"></i>
            </button>

            <form action="{{ route('document.destroy', $document->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" 
                        onclick="return confirm('Delete this document?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>

                    @empty
                        <div class="empty-state">
                            <i class="fas fa-file-pdf"></i>
                            <h5>No Documents</h5>
                            <p class="mb-3">Upload certificates, publications, or other documents</p>
                            <button type="button" class="btn btn-primary" 
                                    data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                <i class="fas fa-plus me-1"></i>Upload Document
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- CV Actions -->
                <div class="sidebar-card">
                    <h5>CV Actions</h5>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('cv.preview', $cv->id) }}" 
                           class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview CV
                        </a>
                        
                        <!-- <a href="{{ route('cv.pdf', $cv->id) }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a> -->
                        
                        @if($cv->is_public && $cv->share_token)
                            <div class="mt-3">
                                <label class="form-label">Shareable Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ route('cv.public', $cv->share_token) }}" 
                                           readonly id="share-link">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                            onclick="copyToClipboard('{{ route('cv.public', $cv->share_token) }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                        
                        <form action="{{ route('cv.share', $cv->id) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-info">
                                <i class="fas fa-share me-2"></i>
                                {{ $cv->is_public ? 'Regenerate Link' : 'Generate Share Link' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('cv.destroy', $cv->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this CV?')" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Delete CV
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- CV Completeness -->
                @php
                    $sections = [
                        'Profile' => $cv->profile ? 100 : 0,
                        'Education' => min(count($cv->educations) * 20, 100),
                        'Clinical' => min(count($cv->clinicals) * 25, 100),
                        'Research' => min(count($cv->researches) * 33, 100),
                        'Awards' => min(count($cv->awards) * 33, 100),
                        'Documents' => min(count($cv->documents) * 50, 100),
                    ];
                    $total = array_sum($sections) / count($sections);
                @endphp
                
                <div class="sidebar-card">
                    <div class="completeness-meter">
                        <div class="completeness-circle">
                            <div class="completeness-circle-inner">
                                {{ round($total) }}%
                            </div>
                        </div>
                        <h5>CV Completeness</h5>
                        <p class="text-muted">Overall progress</p>
                    </div>
                    
                    <div class="mt-4">
                        @foreach($sections as $section => $percent)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $section }}</span>
                                    <span>{{ $percent }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Template Selector -->
                <div class="sidebar-card">
                    <h5>CV Template</h5>
                    <p class="text-muted">Change your CV's appearance</p>
                    
                    <div class="template-selector">
                        <div class="template-option {{ $cv->template == 'template1' ? 'selected' : '' }}" 
                             onclick="changeTemplate('template1')">
                            <div style="font-size: 24px; color: #0d6efd; margin-bottom: 5px;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <small>Modern</small>
                        </div>
                        
                        <div class="template-option {{ $cv->template == 'template2' ? 'selected' : '' }}" 
                             onclick="changeTemplate('template2')">
                            <div style="font-size: 24px; color: #198754; margin-bottom: 5px;">
                                <i class="fas fa-university"></i>
                            </div>
                            <small>Academic</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('cv.preview', $cv->id) }}" class="btn btn-sm btn-outline-primary w-100" target="_blank">
                            <i class="fas fa-eye me-1"></i>Preview Template
                        </a>
                    </div>
                </div>
                
                <!-- Quick Preview -->
                <div class="sidebar-card">
                    <h5>Quick Preview</h5>
                    <div class="preview-mini">
                        <h6>{{ $cv->title }}</h6>
                        <p class="text-muted small">{{ $cv->primary_speciality ?? 'General' }}</p>
                        <hr>
                        <div class="small">
                            <div class="d-flex justify-content-between">
                                <span>Education:</span>
                                <span>{{ count($cv->educations) }} entries</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Clinical:</span>
                                <span>{{ count($cv->clinicals) }} entries</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Research:</span>
                                <span>{{ count($cv->researches) }} entries</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Awards:</span>
                                <span>{{ count($cv->awards) }} entries</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('cvbuilder.modals.basic-info-modal')
@include('cvbuilder.modals.profile-modal')
@include('cvbuilder.modals.education-modal')
@include('cvbuilder.modals.clinical-modal')
@include('cvbuilder.modals.research-modal')
@include('cvbuilder.modals.award-modal')
@include('cvbuilder.modals.document-modal')

<script>
    function changeTemplate(template) {
        if(confirm('Change template to ' + (template === 'template1' ? 'Modern' : 'Academic') + '?')) {
            fetch('{{ route("cv.update", $cv->id) }}', {  // make sure this is cv.update
                method: 'POST',  // Laravel expects POST with _method=PUT
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    template: template,
                    _method: 'PUT'  // Laravel method spoofing
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
    }

    
    // Edit education
    document.querySelectorAll('.edit-education').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addEducationModal'));
            const form = document.querySelector('#addEducationModal form');
            
            form.querySelector('[name="title"]').value = this.dataset.title;
            form.querySelector('[name="institute"]').value = this.dataset.institute;
            form.querySelector('[name="year_from"]').value = this.dataset.year_from;
            form.querySelector('[name="year_to"]').value = this.dataset.year_to;
            form.querySelector('[name="details"]').value = this.dataset.details;
            
            // Change form action for update
            form.action = '{{ route("education.update", "") }}/' + this.dataset.id;
            form.querySelector('input[name="_method"]').value = 'PUT';
            
            modal.show();
        });
    });
    
    // Edit clinical
    document.querySelectorAll('.edit-clinical').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addClinicalModal'));
            const form = document.querySelector('#addClinicalModal form');
            
            form.querySelector('[name="title"]').value = this.dataset.title;
            form.querySelector('[name="institute"]').value = this.dataset.institute;
            form.querySelector('[name="year_from"]').value = this.dataset.year_from;
            form.querySelector('[name="year_to"]').value = this.dataset.year_to;
            form.querySelector('[name="details"]').value = this.dataset.details;
            
            // Change form action for update
            form.action = '{{ route("education.update", "") }}/' + this.dataset.id;
            form.querySelector('input[name="_method"]').value = 'PUT';
            
            modal.show();
        });
    });
    
    // Edit basic info
    document.getElementById('editBasicInfoModal')?.addEventListener('show.bs.modal', function() {
        const form = this.querySelector('form');
        form.querySelector('[name="title"]').value = '{{ $cv->title }}';
        form.querySelector('[name="primary_speciality"]').value = '{{ $cv->primary_speciality }}';
        form.querySelector('[name="summary"]').value = `{{ $cv->summary }}`;
        form.querySelector('[name="template"]').value = '{{ $cv->template }}';
        form.querySelector('[name="is_public"]').checked = {{ $cv->is_public ? 'true' : 'false' }};
    });
    
    // Edit profile
    document.getElementById('editProfileModal')?.addEventListener('show.bs.modal', function (event) {
        const form = this.querySelector('form');

        @if($cv->profile)
            // ✏️ EDIT MODE
            form.action = '{{ route("cv-profile.update", $cv->profile->id) }}';

            form.querySelector('[name="_method"]').value = 'PUT';

            form.querySelector('[name="full_name"]').value = '{{ $cv->user->name }}';
            form.querySelector('[name="email"]').value = '{{ $cv->user->email }}';
            form.querySelector('[name="profile_image"]').value = '{{ $cv->user->profile_image }}';

            form.querySelector('[name="university"]').value = '{{ $cv->profile->university }}';
            form.querySelector('[name="class_year"]').value = '{{ $cv->profile->class_year }}';
            form.querySelector('[name="primary_interest"]').value = '{{ $cv->profile->primary_interest }}';
            form.querySelector('[name="phone"]').value = '{{ $cv->profile->phone }}';

        @else
            // ➕ ADD MODE
            form.action = '{{ route("cv-profile.store") }}';

            // method reset
            if (form.querySelector('[name="_method"]')) {
                form.querySelector('[name="_method"]').remove();
            }

            // 🔥 DEFAULT USER VALUES
            form.querySelector('[name="full_name"]').value = '{{ $cv->user->name }}';
            form.querySelector('[name="email"]').value = '{{ $cv->user->email }}';
            form.querySelector('[name="profile_image"]').value = '{{ $cv->user->profile_image }}';

            // profile fields empty
            form.querySelector('[name="university"]').value = '';
            form.querySelector('[name="class_year"]').value = '';
            form.querySelector('[name="primary_interest"]').value = '';
            form.querySelector('[name="phone"]').value = '';
        @endif
    });
</script>

@endsection