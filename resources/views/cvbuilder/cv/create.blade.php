@extends('layouts.app')

@section('title', 'Create New CV')

@section('content')

<style>
    .create-cv-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    
    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 30%;
    }
    
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
    }
    
    .step.active .step-circle {
        background: #0d6efd;
        color: white;
    }
    
    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .step.active .step-label {
        color: #0d6efd;
        font-weight: bold;
    }
    
    .form-section {
        display: none;
    }
    
    .form-section.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .template-option {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }
    
    .template-option:hover {
        border-color: #0d6efd;
        transform: translateY(-5px);
    }
    
    .template-option.selected {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    
    .template-preview {
        height: 200px;
        background: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 15px;
        overflow: hidden;
        position: relative;
    }
    
    .preview-content {
        padding: 15px;
    }
    
    .preview-content h5 {
        margin: 0 0 10px 0;
        color: #2c3e50;
    }
    
    .preview-content p {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .btn-group-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }
    
    .specialty-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    
    .specialty-tag {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .specialty-tag:hover {
        background: #e7f1ff;
        border-color: #0d6efd;
    }
    
    .specialty-tag.selected {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>

<div class="container mt-4">
    <div class="create-cv-container">
        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Basic Info</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Template</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Preview</div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('cv.store') }}" id="cvCreateForm">
            @csrf
            
            <!-- Step 1: Basic Info -->
            <div class="form-section active" id="step1">
                <h4 class="mb-4">Basic Information</h4>
                
                <div class="mb-4">
                    <label for="cvTitle" class="form-label">CV Title *</label>
                    <input type="text" class="form-control form-control-lg" 
                           id="cvTitle" name="title" required 
                           placeholder="e.g., Medical Residency Application CV">
                    <div class="form-text">Give your CV a descriptive title</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Primary Speciality</label>
                    <input type="text" class="form-control" 
                           id="primary_speciality" name="primary_speciality"
                           placeholder="e.g., Internal Medicine, Surgery">
                    
                    <div class="specialty-tags">
                        @php
                            $specialties = [
                                'Internal Medicine', 'Surgery', 'Pediatrics', 'Obstetrics & Gynecology',
                                'Psychiatry', 'Radiology', 'Anesthesiology', 'Emergency Medicine',
                                'Pathology', 'Neurology', 'Cardiology', 'Oncology'
                            ];
                        @endphp
                        
                        @foreach($specialties as $specialty)
                            <div class="specialty-tag" onclick="document.getElementById('primary_speciality').value = '{{ $specialty }}'; 
                                                                 document.querySelectorAll('.specialty-tag').forEach(tag => tag.classList.remove('selected'));
                                                                 this.classList.add('selected');">
                                {{ $specialty }}
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="cvSummary" class="form-label">Professional Summary</label>
                    <textarea class="form-control" id="cvSummary" name="summary" 
                              rows="4" placeholder="Briefly describe your professional background and career objectives..."></textarea>
                    <div class="form-text">This will appear at the top of your CV</div>
                </div>
            </div>
            
            <!-- Step 2: Template Selection -->
            <div class="form-section" id="step2">
                <h4 class="mb-4">Choose a Template</h4>
                <p class="text-muted mb-4">Select a template that best fits your style. You can change it later.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="template-option" onclick="selectTemplate('template1')" id="template1-option">
                            <div class="template-preview">
                                <div class="preview-content">
                                    <h5>Modern Professional</h5>
                                    <p>Clean, modern design with focus on education and clinical experience</p>
                                    <div style="display: flex; gap: 5px; margin-top: 10px;">
                                        <div style="width: 30%; height: 3px; background: #0d6efd;"></div>
                                        <div style="width: 20%; height: 3px; background: #0d6efd;"></div>
                                        <div style="width: 15%; height: 3px; background: #0d6efd;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template" 
                                       value="template1" id="template1" checked>
                                <label class="form-check-label" for="template1">
                                    Template 1 - Modern Professional
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="template-option" onclick="selectTemplate('template2')" id="template2-option">
                            <div class="template-preview">
                                <div class="preview-content">
                                    <h5>Academic Focus</h5>
                                    <p>Emphasizes research, publications and academic achievements</p>
                                    <div style="display: flex; gap: 5px; margin-top: 10px;">
                                        <div style="width: 40%; height: 3px; background: #198754;"></div>
                                        <div style="width: 30%; height: 3px; background: #198754;"></div>
                                        <div style="width: 20%; height: 3px; background: #198754;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template" 
                                       value="template2" id="template2">
                                <label class="form-check-label" for="template2">
                                    Template 2 - Academic Focus
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> You can preview and change templates anytime from the CV edit page.
                </div>
            </div>
            
            <!-- Step 3: Preview & Create -->
            <div class="form-section" id="step3">
                <h4 class="mb-4">Review & Create</h4>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">CV Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Title</h6>
                                <p id="preview-title" class="text-muted">Not set</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Speciality</h6>
                                <p id="preview-speciality" class="text-muted">Not set</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Template</h6>
                                <p id="preview-template" class="text-muted">Modern Professional</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Summary</h6>
                                <p id="preview-summary" class="text-muted" style="max-height: 100px; overflow-y: auto;"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Ready to create!</strong> After creation, you'll be redirected to add more details like education, clinical experience, etc.
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="btn-group-nav">
                <button type="button" class="btn btn-outline-secondary" id="btnPrev" onclick="prevStep()" style="display: none;">
                    <i class="fas fa-arrow-left me-2"></i>Previous
                </button>
                
                <div>
                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="btnNext" onclick="nextStep()">
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                        <i class="fas fa-check me-2"></i>Create CV
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;
    
    function selectTemplate(template) {
        // Remove selected class from all template options
        document.querySelectorAll('.template-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Add selected class to clicked template
        document.getElementById(`${template}-option`).classList.add('selected');
        
        // Update radio button
        document.getElementById(template).checked = true;
        
        // Update preview
        if(currentStep === 3) {
            updatePreview();
        }
    }
    
    function updatePreview() {
        document.getElementById('preview-title').textContent = 
            document.getElementById('cvTitle').value || 'Not set';
        document.getElementById('preview-speciality').textContent = 
            document.getElementById('primary_speciality').value || 'Not set';
        document.getElementById('preview-summary').textContent = 
            document.getElementById('cvSummary').value || 'Not set';
        
        const selectedTemplate = document.querySelector('input[name="template"]:checked').value;
        document.getElementById('preview-template').textContent = 
            selectedTemplate === 'template1' ? 'Modern Professional' : 'Academic Focus';
    }
    
    function nextStep() {
        // Validate current step
        if(currentStep === 1) {
            const title = document.getElementById('cvTitle').value.trim();
            if(!title) {
                alert('Please enter a CV title');
                document.getElementById('cvTitle').focus();
                return;
            }
        }
        
        // Hide current step
        document.getElementById(`step${currentStep}`).classList.remove('active');
        document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
        
        // Move to next step
        currentStep++;
        
        // Show next step
        document.getElementById(`step${currentStep}`).classList.add('active');
        document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
        
        // Update navigation buttons
        document.getElementById('btnPrev').style.display = 'inline-block';
        
        if(currentStep === totalSteps) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnSubmit').style.display = 'inline-block';
            updatePreview();
        }
    }
    
    function prevStep() {
        // Hide current step
        document.getElementById(`step${currentStep}`).classList.remove('active');
        document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
        
        // Move to previous step
        currentStep--;
        
        // Show previous step
        document.getElementById(`step${currentStep}`).classList.add('active');
        document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
        
        // Update navigation buttons
        if(currentStep === 1) {
            document.getElementById('btnPrev').style.display = 'none';
        }
        
        document.getElementById('btnNext').style.display = 'inline-block';
        document.getElementById('btnSubmit').style.display = 'none';
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        selectTemplate('template1');
    });
</script>

@endsection