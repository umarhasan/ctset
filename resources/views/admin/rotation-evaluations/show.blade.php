@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('rotation-evaluations.index') }}" class="text-decoration-none">Evaluation Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Form Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden">
                <!-- Gradient Header -->
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fw-bold">
                                <i class="fas fa-clipboard-check me-2"></i>{{ $evaluation->title }}
                            </h2>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-book me-2"></i>{{ $evaluation->course_title }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('rotation-evaluations.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            @can('rotation-evaluations-edit')
                            <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $evaluation->id }})">
                                <i class="fas fa-edit me-2"></i>Edit
                            </button>
                            @endcan
                            @can('rotation-evaluations-delete')
                            <button class="btn btn-danger btn-sm" onclick="deleteRecord({{ $evaluation->id }})">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Status Bar -->
                <div class="card-body bg-light p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="status-item">
                                    <span class="text-muted me-2">Status:</span>
                                    @if($evaluation->status == 'active')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                                <div class="status-item">
                                    <span class="text-muted me-2">Form ID:</span>
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-hashtag me-1"></i>EV-{{ str_pad($evaluation->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <div class="status-item">
                                    <span class="text-muted me-2">Sections:</span>
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-layer-group me-1"></i>{{ count($evaluation->sections ?? []) }}
                                    </span>
                                </div>
                                <div class="status-item">
                                    <span class="text-muted me-2">Questions:</span>
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="fas fa-question-circle me-1"></i>
                                        {{ collect($evaluation->sections ?? [])->sum(function($s) { return count($s['subitems'] ?? []); }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Created: {{ $evaluation->created_at->format('M d, Y h:i A') }}<br>
                                <i class="fas fa-sync-alt me-1"></i>Updated: {{ $evaluation->updated_at->format('M d, Y h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-layer-group fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Sections</h6>
                            <h3 class="mb-0 fw-bold">{{ count($evaluation->sections ?? []) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-question-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Questions</h6>
                            <h3 class="mb-0 fw-bold">{{ collect($evaluation->sections ?? [])->sum(function($s) { return count($s['subitems'] ?? []); }) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Active Sections</h6>
                            <h3 class="mb-0 fw-bold">{{ count($evaluation->sections ?? []) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Last Updated</h6>
                            <h6 class="mb-0 fw-bold">{{ $evaluation->updated_at->format('M d, Y') }}</h6>
                            <small class="text-muted">{{ $evaluation->updated_at->format('h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Structure Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill" id="formTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="structure-tab" data-bs-toggle="tab" data-bs-target="#structure" type="button" role="tab">
                        <i class="fas fa-sitemap me-2"></i>Form Structure
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview" type="button" role="tab">
                        <i class="fas fa-eye me-2"></i>Live Preview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                        <i class="fas fa-chart-bar me-2"></i>Analytics
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="formTabsContent">
        <!-- Structure Tab -->
        <div class="tab-pane fade show active" id="structure" role="tabpanel">
            @if(isset($evaluation->sections) && count($evaluation->sections) > 0)
                @foreach($evaluation->sections as $sectionIndex => $section)
                <div class="card section-card border-0 shadow-sm mb-4">
                    <!-- Section Header -->
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="section-letter-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">{{ chr(65 + $sectionIndex) }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-1">{{ $section['section_title'] ?? 'Untitled Section' }}</h4>
                                @if(!empty($section['description']))
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>{{ $section['description'] }}
                                </p>
                                @endif
                            </div>
                            <span class="badge bg-light text-dark px-3 py-2">
                                <i class="fas fa-list me-1"></i>{{ count($section['subitems'] ?? []) }} Items
                            </span>
                        </div>
                    </div>

                    <!-- Section Content -->
                    <div class="card-body p-0">
                        @if(isset($section['subitems']) && count($section['subitems']) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="60" class="text-center">#</th>
                                            <th>Question / Item</th>
                                            <th width="200">Input Type</th>
                                            <th width="300">Scale Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($section['subitems'] as $itemIndex => $subitem)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $itemIndex + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-question-circle text-muted me-2"></i>
                                                    {{ $subitem['subitem_text'] ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $typeLabels = [
                                                        'text' => ['icon' => 'fa-keyboard', 'label' => 'Text Input', 'color' => 'info'],
                                                        'checkbox' => ['icon' => 'fa-check-square', 'label' => 'Checkbox', 'color' => 'success'],
                                                        'radio' => ['icon' => 'fa-dot-circle', 'label' => 'Radio Button', 'color' => 'warning'],
                                                        'scale_5' => ['icon' => 'fa-sliders-h', 'label' => '5-Point Scale', 'color' => 'primary'],
                                                        'scale_5_with_desc' => ['icon' => 'fa-chart-line', 'label' => '5-Point Scale with Description', 'color' => 'purple']
                                                    ];
                                                    $type = $subitem['input_type'] ?? 'text';
                                                    $typeInfo = $typeLabels[$type] ?? ['icon' => 'fa-question', 'label' => ucfirst($type), 'color' => 'secondary'];
                                                @endphp
                                                <span class="badge bg-{{ $typeInfo['color'] }} bg-opacity-10 text-{{ $typeInfo['color'] }} px-3 py-2">
                                                    <i class="fas {{ $typeInfo['icon'] }} me-1"></i>
                                                    {{ $typeInfo['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(($subitem['input_type'] ?? '') == 'scale_5_with_desc' && isset($subitem['scale_desc']))
                                                    <div class="scale-preview d-flex flex-wrap gap-1">
                                                        @foreach($subitem['scale_desc'] as $scaleIndex => $desc)
                                                            @if(!empty($desc))
                                                                <span class="badge bg-secondary px-2 py-1" data-bs-toggle="tooltip" title="{{ $desc }}">
                                                                    {{ $scaleIndex + 1 }}: {{ Str::limit($desc, 20) }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @elseif($type == 'scale_5')
                                                    <span class="text-muted">1-5 Scale</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-circle fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No items in this section</p>
                            </div>
                        @endif
                    </div>

                    <!-- Section Footer -->
                    <div class="card-footer bg-light py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Created: {{ $evaluation->created_at->format('M d, Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-1"></i>Section {{ chr(65 + $sectionIndex) }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="fas fa-layer-group fa-3x text-muted"></i>
                            </div>
                            <h4>No Sections Found</h4>
                            <p class="text-muted">This form doesn't have any sections yet.</p>
                            @can('rotation-evaluations-edit')
                            <button class="btn btn-primary" onclick="openEditModal({{ $evaluation->id }})">
                                <i class="fas fa-edit me-2"></i>Add Sections
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Preview Tab -->
        <div class="tab-pane fade" id="preview" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-eye text-primary me-2"></i>
                        <h5 class="mb-0">Form Preview</h5>
                        <span class="badge bg-warning ms-3">Preview Mode</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="preview-container bg-light p-4 rounded-3">
                        <!-- Form Header -->
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-2">{{ $evaluation->title }}</h2>
                            <p class="text-muted">{{ $evaluation->course_title }}</p>
                            <div class="d-flex justify-content-center gap-3">
                                <span class="badge bg-info">Rotation Evaluation</span>
                                <span class="badge bg-secondary">{{ date('Y') }}</span>
                            </div>
                        </div>

                        @if(isset($evaluation->sections) && count($evaluation->sections) > 0)
                            @foreach($evaluation->sections as $sectionIndex => $section)
                            <div class="preview-section mb-5">
                                <!-- Section Title -->
                                <div class="section-preview-header mb-3">
                                    <h4 class="border-start border-4 border-primary ps-3">
                                        <span class="text-primary">{{ chr(65 + $sectionIndex) }}.</span>
                                        {{ $section['section_title'] ?? '' }}
                                    </h4>
                                    @if(!empty($section['description']))
                                        <p class="text-muted ms-4">{{ $section['description'] }}</p>
                                    @endif
                                </div>

                                <!-- Questions -->
                                @if(isset($section['subitems']) && count($section['subitems']) > 0)
                                    @foreach($section['subitems'] as $itemIndex => $subitem)
                                    <div class="question-item mb-4 ms-4">
                                        <p class="fw-medium mb-2">
                                            <span class="text-muted me-2">{{ $itemIndex + 1 }}.</span>
                                            {{ $subitem['subitem_text'] ?? '' }}
                                        </p>

                                        @php
                                            $inputType = $subitem['input_type'] ?? 'text';
                                        @endphp

                                        <div class="answer-preview">
                                            @if($inputType == 'text')
                                                <div class="mb-3">
                                                    <input type="text" class="form-control form-control-sm" placeholder="Enter your answer here..." disabled style="background: #f9f9f9; max-width: 500px;">
                                                </div>

                                            @elseif($inputType == 'checkbox')
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" disabled id="preview-check-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                    <label class="form-check-label text-muted" for="preview-check-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                        Yes, I agree
                                                    </label>
                                                </div>

                                            @elseif($inputType == 'radio')
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="preview-radio-1-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                        <label class="form-check-label text-muted" for="preview-radio-1-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                            Option 1
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="preview-radio-2-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                        <label class="form-check-label text-muted" for="preview-radio-2-{{ $sectionIndex }}-{{ $itemIndex }}">
                                                            Option 2
                                                        </label>
                                                    </div>
                                                </div>

                                            @elseif($inputType == 'scale_5')
                                                <div class="d-flex gap-3 align-items-center">
                                                    @for($i=1; $i<=5; $i++)
                                                        <div class="text-center">
                                                            <div class="mb-1">{{ $i }}</div>
                                                            <input class="form-check-input" type="radio" disabled>
                                                        </div>
                                                    @endfor
                                                </div>

                                            @elseif($inputType == 'scale_5_with_desc' && isset($subitem['scale_desc']))
                                                <div class="row g-2">
                                                    @foreach($subitem['scale_desc'] as $scaleIndex => $desc)
                                                        <div class="col-md-2">
                                                            <div class="scale-card text-center p-2 border rounded bg-white">
                                                                <div class="fw-bold mb-1">{{ $scaleIndex + 1 }}</div>
                                                                <small class="text-muted d-block mb-2">{{ $desc ?: 'No description' }}</small>
                                                                <input class="form-check-input" type="radio" disabled>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            @endforeach

                            <!-- Submit Button -->
                            <div class="text-center mt-5">
                                <button class="btn btn-primary px-5 py-2" disabled>
                                    <i class="fas fa-paper-plane me-2"></i>Submit Evaluation
                                </button>
                                <p class="text-muted small mt-2">This is a preview. Actual form may look different.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Form Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Analytics Cards -->
                        <div class="col-md-3">
                            <div class="analytics-card bg-light p-3 rounded-3 text-center">
                                <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                                <h3 class="mb-1">0</h3>
                                <p class="text-muted mb-0">Total Views</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="analytics-card bg-light p-3 rounded-3 text-center">
                                <i class="fas fa-pencil-alt fa-2x text-success mb-2"></i>
                                <h3 class="mb-1">0</h3>
                                <p class="text-muted mb-0">Submissions</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="analytics-card bg-light p-3 rounded-3 text-center">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h3 class="mb-1">0</h3>
                                <p class="text-muted mb-0">Avg. Time</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="analytics-card bg-light p-3 rounded-3 text-center">
                                <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                                <h3 class="mb-1">0%</h3>
                                <p class="text-muted mb-0">Completion Rate</p>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder Chart -->
                    <div class="mt-4 text-center py-5 bg-light rounded-3">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Analytics data will appear here</h5>
                        <p class="text-muted small">Track form performance and submissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal (Same as before) -->
<div class="modal fade" id="masterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="masterForm">
            @csrf
            <input type="hidden" id="record_id" value="{{ $evaluation->id }}">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Evaluation Form
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Form fields same as before -->
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Form Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" value="{{ $evaluation->title }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Course Title</label>
                            <input type="text" name="course_title" id="course_title" class="form-control" value="{{ $evaluation->course_title }}" required>
                        </div>
                    </div>

                    <!-- Sections Container -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-bold mb-0">Form Sections</label>
                            <button type="button" class="btn btn-success btn-sm" onclick="addSection()">
                                <i class="fas fa-plus me-1"></i>Add Section
                            </button>
                        </div>
                        <div id="sectionsContainer"></div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active" {{ $evaluation->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $evaluation->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Update Form
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Templates (same as before) -->
<template id="sectionTemplate">
    <div class="section-item card mb-3 border">
        <div class="card-header bg-light d-flex align-items-center">
            <span class="section-letter fw-bold me-2 text-primary">A.</span>
            <input type="text" class="form-control form-control-sm section-title" placeholder="Section Title" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-section">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <textarea class="form-control section-description mb-3" placeholder="Section Description (optional)" rows="2"></textarea>
            <div class="subitems-container mb-2"></div>
            <button type="button" class="btn btn-outline-primary btn-sm add-subitem">
                <i class="fas fa-plus me-1"></i>Add Question
            </button>
        </div>
    </div>
</template>

<template id="subitemTemplate">
    <div class="subitem-item bg-light p-2 rounded mb-2">
        <div class="d-flex align-items-center">
            <span class="subitem-number fw-bold me-2 text-success">1.</span>
            <input type="text" class="form-control form-control-sm subitem-text" placeholder="Question text">
            <select class="form-control form-control-sm ms-2 subitem-type" style="width: 200px;">
                <option value="text">📝 Text Input</option>
                <option value="checkbox">✅ Checkbox</option>
                <option value="radio">🔘 Radio Button</option>
                <option value="scale_5">📊 5-Point Scale</option>
                <option value="scale_5_with_desc">📈 5-Point Scale with Description</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-subitem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="scale-desc-container mt-2" style="display:none;">
            <div class="row">
                @for($i=1;$i<=5;$i++)
                <div class="col-md-2 mb-2">
                    <label class="small">Value {{$i}}:</label>
                    <input type="text" class="form-control form-control-sm scale-desc" placeholder="Desc {{$i}}">
                </div>
                @endfor
            </div>
        </div>
    </div>
</template>

<style>
/* Custom Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.section-letter-circle {
    width: 40px;
    height: 40px;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card {
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.analytics-card {
    transition: all 0.3s;
}

.analytics-card:hover {
    background: white !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.scale-card {
    transition: all 0.3s;
}

.scale-card:hover {
    border-color: #667eea !important;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.preview-container {
    border: 2px dashed #dee2e6;
}

.section-card {
    border-left: 4px solid transparent;
    transition: all 0.3s;
}

.section-card:hover {
    border-left-color: #667eea;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important;
}

.badge.bg-purple {
    background-color: #9c27b0 !important;
    color: white;
}

.text-purple {
    color: #9c27b0 !important;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-card {
    animation: fadeIn 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .section-letter-circle {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    const masterModal = new bootstrap.Modal('#masterModal');

    // Load sections for edit
    function loadSectionsForEdit() {
        var sections = @json($evaluation->sections ?? []);
        
        if (sections && sections.length > 0) {
            sections.forEach(function(section, sectionIndex) {
                addSection();
                const lastSection = $('#sectionsContainer .section-item').last();
                
                lastSection.find('.section-title').val(section.section_title || '');
                lastSection.find('.section-description').val(section.description || '');
                
                const subitemsContainer = lastSection.find('.subitems-container');
                subitemsContainer.empty();
                
                if(section.subitems && section.subitems.length > 0) {
                    section.subitems.forEach(function(subitem, subitemIndex) {
                        addSubitemToContainer(subitemsContainer, subitem, subitemIndex);
                    });
                } else {
                    addSubitemToContainer(subitemsContainer, null, 0);
                }
            });
        } else {
            addSection();
        }
        
        updateSectionLetters();
        updateSubitemNumbers();
        addSubitemEvents();
    }

    // Add subitem to container
    function addSubitemToContainer(container, subitemData = null, index = 0) {
        const template = $('#subitemTemplate').html();
        const $newSubitem = $(template);
        
        $newSubitem.find('.subitem-number').text((index + 1) + '.');
        
        if (subitemData) {
            $newSubitem.find('.subitem-text').val(subitemData.subitem_text || '');
            $newSubitem.find('.subitem-type').val(subitemData.input_type || 'text');
            
            if (subitemData.input_type === 'scale_5_with_desc' && subitemData.scale_desc) {
                $newSubitem.find('.scale-desc-container').show();
                subitemData.scale_desc.forEach(function(desc, i) {
                    $newSubitem.find('.scale-desc').eq(i).val(desc || '');
                });
            }
        }
        
        container.append($newSubitem);
    }

    // Open Edit Modal
    window.openEditModal = function(id){
        $('#sectionsContainer').html('');
        loadSectionsForEdit();
        masterModal.show();
    };

    // Add Section
    window.addSection = function(){
        const template = $('#sectionTemplate').html();
        $('#sectionsContainer').append(template);
        updateSectionLetters();
        addSectionEventListeners();
        
        const lastSection = $('#sectionsContainer .section-item').last();
        const subitemsContainer = lastSection.find('.subitems-container');
        if (subitemsContainer.children().length === 0) {
            addSubitemToContainer(subitemsContainer, null, 0);
        }
    };

    // Add section event listeners
    function addSectionEventListeners(){
        $('.remove-section').off('click').on('click', function(){
            if($('.section-item').length > 1){
                $(this).closest('.section-item').remove();
                updateSectionLetters();
                updateSubitemNumbers();
            } else {
                alert('At least one section is required');
            }
        });

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
        $('.subitem-type').off('change').on('change', function(){
            const item = $(this).closest('.subitem-item');
            const selectedType = $(this).val();
            
            if(selectedType === 'scale_5_with_desc'){
                item.find('.scale-desc-container').slideDown();
            } else {
                item.find('.scale-desc-container').slideUp();
            }
        });

        $('.remove-subitem').off('click').on('click', function(){
            const container = $(this).closest('.subitems-container');
            if(container.find('.subitem-item').length > 1){
                $(this).closest('.subitem-item').remove();
                updateSubitemNumbers();
            } else {
                alert('At least one question is required per section');
            }
        });
    }

    // Update section letters
    function updateSectionLetters(){
        $('.section-item').each(function(index){
            const letter = String.fromCharCode(65 + index);
            $(this).find('.section-letter').text(letter + '.');
        });
    }

    // Update subitem numbers
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
        let url = `/rotation-evaluations/${id}`;
        
        if ($('#sectionsContainer .section-item').length === 0) {
            alert('At least one section is required');
            return;
        }
        
        let sections = [];
        let isValid = true;
        
        $('#sectionsContainer .section-item').each(function(sectionIndex){
            let sectionTitle = $(this).find('.section-title').val().trim();
            if (!sectionTitle) {
                isValid = false;
                alert(`Section ${String.fromCharCode(65 + sectionIndex)} title is required`);
                return false;
            }
            
            let subitems = [];
            $(this).find('.subitem-item').each(function(){
                let subitemText = $(this).find('.subitem-text').val().trim();
                if (!subitemText) {
                    isValid = false;
                    alert('All question texts are required');
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
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                title: $('#title').val().trim(),
                course_title: $('#course_title').val().trim(),
                status: $('#status').val(),
                sections: sections
            },
            success: function(response){
                if(response.success){
                    alert(response.message || 'Form updated successfully');
                    masterModal.hide();
                    location.reload();
                } else {
                    alert(response.message || 'Something went wrong');
                }
            },
            error: function(xhr){
                let errorMessage = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                alert('Error: ' + errorMessage);
                console.error(xhr);
            }
        });
    });

    // Delete Record
    window.deleteRecord = function(id){
        if(confirm('Are you sure you want to delete this form? This action cannot be undone!')) {
            $.ajax({
                url: `/rotation-evaluations/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response){
                    if(response.success){
                        alert(response.message || 'Form deleted successfully');
                        window.location.href = '{{ route("rotation-evaluations.index") }}';
                    } else {
                        alert(response.message || 'Something went wrong');
                    }
                },
                error: function(xhr){
                    alert('Failed to delete form');
                    console.error(xhr);
                }
            });
        }
    };

    // Reset modal on hide
    $('#masterModal').on('hidden.bs.modal', function() {
        $('#sectionsContainer').html('');
    });
});
</script>
@endpush