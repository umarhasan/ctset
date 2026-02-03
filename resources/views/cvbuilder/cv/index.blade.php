@extends('layouts.app')

@section('title', 'My CVs')

@section('content')

<style>
    .cvs-container {
        padding: 20px 0;
    }
    
    .cv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .cv-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .cv-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        border-color: #0d6efd;
    }
    
    .cv-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        position: relative;
    }
    
    .cv-card-body {
        padding: 20px;
    }
    
    .cv-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: white;
    }
    
    .cv-specialty {
        font-size: 0.9rem;
        opacity: 0.9;
        color: rgba(255,255,255,0.9);
    }
    
    .cv-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        text-align: center;
        margin: 15px 0;
    }
    
    .stat-item {
        padding: 10px;
    }
    
    .stat-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: #0d6efd;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    .cv-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }
    
    .cv-actions {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    .dropdown-toggle::after {
        display: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    
    .create-cv-card {
        border: 2px dashed #0d6efd;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .create-cv-card:hover {
        background: #e7f1ff;
        border-color: #0b5ed7;
    }
    
    .create-cv-content {
        text-align: center;
        color: #0d6efd;
    }
    
    .create-cv-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }
    
    .badge-template {
        background: #6c757d;
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
    }
    
    .badge-template.template1 {
        background: #0d6efd;
    }
    
    .badge-template.template2 {
        background: #198754;
    }
</style>

<div class="container cvs-container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>My CVs</h2>
            <p class="text-muted">Manage all your curriculum vitae</p>
        </div>
        <div>
            <a href="{{ route('cv.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New CV
            </a>
        </div>
    </div>

    <!-- CV Grid -->
    <div class="cv-grid">
        <!-- Create New CV Card -->
        <div class="cv-card create-cv-card" onclick="window.location.href='{{ route('cv.create') }}'">
            <div class="create-cv-content">
                <div class="create-cv-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4>Create New CV</h4>
                <p class="text-muted">Start building your professional CV</p>
            </div>
        </div>

        <!-- CV Cards -->
        @forelse($cvs as $cv)
            <div class="cv-card">
                <div class="cv-card-header">
                    <div class="cv-actions">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('cv.edit', $cv->id) }}">
                                        <i class="fas fa-edit me-2"></i>Edit CV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('cv.preview', $cv->id) }}" target="_blank">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </a>
                                </li>
                                <!-- <li>
                                    <a class="dropdown-item" href="{{ route('cv.pdf', $cv->id) }}">
                                        <i class="fas fa-download me-2"></i>Download PDF
                                    </a>
                                </li> -->
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('cv.share', $cv->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-share me-2"></i>
                                            {{ $cv->is_public ? 'Regenerate Link' : 'Make Public' }}
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('cv.destroy', $cv->id) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Delete this CV?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i>Delete CV
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h4 class="cv-title">{{ $cv->title }}</h4>
                    <div class="cv-specialty">{{ $cv->primary_speciality ?? 'General CV' }}</div>
                    
                    <div class="mt-2">
                        <span class="badge-template {{ $cv->template }}">
                            {{ $cv->template == 'template1' ? 'Modern' : 'Academic' }}
                        </span>
                        @if($cv->is_public)
                            <span class="badge bg-success">
                                <i class="fas fa-link me-1"></i>Public
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-lock me-1"></i>Private
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="cv-card-body">
                    @if($cv->summary)
                        <p class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5;">
                            {{ Str::limit($cv->summary, 100) }}
                        </p>
                    @endif
                    
                    <div class="cv-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $cv->educations->count() }}</div>
                            <div class="stat-label">Education</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $cv->clinicals->count() }}</div>
                            <div class="stat-label">Clinical</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $cv->researches->count() }}</div>
                            <div class="stat-label">Research</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $cv->awards->count() }}</div>
                            <div class="stat-label">Awards</div>
                        </div>
                    </div>
                    
                    <div class="cv-meta">
                        <div>
                            <small class="text-muted">
                                Updated {{ $cv->updated_at->diffForHumans() }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('cv.edit', $cv->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
                
                @if($cv->is_public && $cv->share_token)
                    <div class="cv-card-footer bg-light" style="padding: 15px; border-top: 1px solid #e9ecef;">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" 
                                   value="{{ route('cv.public', $cv->share_token) }}" 
                                   readonly id="share-link-{{ $cv->id }}">
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="copyToClipboard('{{ route('cv.public', $cv->share_token) }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <!-- Show only create card when empty -->
            <script>
                // Hide create card and show empty state
                document.addEventListener('DOMContentLoaded', function() {
                    const createCard = document.querySelector('.create-cv-card');
                    const cvGrid = document.querySelector('.cv-grid');
                    
                    createCard.style.display = 'none';
                    
                    const emptyState = document.createElement('div');
                    emptyState.className = 'empty-state';
                    emptyState.style.gridColumn = '1 / -1';
                    emptyState.innerHTML = `
                        <div class="empty-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>No CVs Yet</h3>
                        <p class="text-muted mb-4">Create your first CV to get started</p>
                        <a href="{{ route('cv.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Create Your First CV
                        </a>
                    `;
                    
                    cvGrid.appendChild(emptyState);
                });
            </script>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($cvs->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="CV pagination">
                <ul class="pagination">
                    @if($cvs->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $cvs->previousPageUrl() }}">Previous</a>
                        </li>
                    @endif

                    @foreach(range(1, $cvs->lastPage()) as $page)
                        <li class="page-item {{ $page == $cvs->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $cvs->url($page) }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if($cvs->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $cvs->nextPageUrl() }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
</div>

<script>
    // Copy share link functionality
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                <strong>Copied!</strong> Share link copied to clipboard.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                alert.remove();
            }, 3000);
        });
    }
</script>

@endsection