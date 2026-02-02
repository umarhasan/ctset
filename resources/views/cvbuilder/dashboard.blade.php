@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
body {
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

.navbar-brand {
    font-weight: bold;
    color: #0d6efd !important;
}

.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.section-title {
    font-weight: bold;
    color: #2c3e50;
    border-bottom: 2px solid #0d6efd;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.completeness-box {
    background: #eaf3fb;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s;
}

.completeness-box:hover {
    transform: translateY(-5px);
}

.footer-text {
    text-align: center;
    font-size: 12px;
    color: gray;
    margin-top: 30px;
}

.quick-box {
    height: 100px;
    background: white;
    border-radius: 10px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    border: 1px solid #dee2e6;
}

.quick-box:hover {
    background: #0d6efd;
    color: white;
    transform: scale(1.05);
}

.quick-box i {
    font-size: 24px;
    margin-bottom: 10px;
}

.milestone-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.activity-box {
    background: #f1f1f1;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 10px;
}

.manage-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.3s;
    border-left: 4px solid #0d6efd;
}

.manage-box:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.profile-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}

.stat-card {
    text-align: center;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card h2 {
    font-size: 2.5rem;
    color: #0d6efd;
    margin: 10px 0;
}

.badge-custom {
    background: #0d6efd;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.cv-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
}

.cv-card:hover {
    border-color: #0d6efd;
}

</style>

<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Welcome back to your portfolio, {{ Auth::user()->name }}!</h4>
            <p class="text-muted">Track your progress, add experiences, and build your residency-ready CV.</p>
        </div>

        <div>
            <a href="{{ route('cv.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-1"></i>Build New CV
            </a>
            <a href="{{ route('cv.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-1"></i>View All CVs
            </a>
        </div>
    </div>

    {{-- Profile & Completeness --}}
    <div class="row mt-4">
        {{-- Profile Card --}}
        <div class="col-md-4">
            <div class="card p-3 profile-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="text-white">Your Profile</h6>
                    @php
                        $userCv = auth()->user()->cvs->first();
                    @endphp

                    @if($userCv)
                        <a href="{{ route('cv.edit', $userCv->id) }}" class="text-white">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @else
                        <a href="{{ route('cv.create') }}" class="text-white">
                            <i class="fas fa-plus"></i> Create CV
                        </a>
                    @endif
                </div>

                <div class="mt-3 text-center">
                    <div class="mb-3">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); 
                                  border-radius: 50%; display: inline-flex; align-items: center; 
                                  justify-content: center; font-size: 32px; color: white;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <strong class="fs-5">{{ Auth::user()->name }}</strong>
                    <p class="mb-1">{{ Auth::user()->institution ?? 'Institution not set' }}</p>
                    <p class="mb-3">Class of {{ Auth::user()->graduation_year ?? 'Year not set' }}</p>

                    @if(Auth::user()->specialty)
                        <span class="badge bg-light text-dark">{{ Auth::user()->specialty }}</span>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card mt-3 p-3">
                <h6>Quick Stats</h6>
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <i class="fas fa-file-alt text-primary"></i>
                            <h5>{{ $cvCount }}</h5>
                            <small>Total CVs</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <i class="fas fa-download text-success"></i>
                            <h5>{{ $documentsCount }}</h5>
                            <small>Documents</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>CV Completeness</h6>
                    @php
                        $totalItems = $educationCount + $clinicalCount + $researchCount + $awardCount + $documentsCount;
                        $totalPossible = 50;
                        $completeness = $totalPossible ? min(round(($totalItems / $totalPossible) * 100), 100) : 0;
                        
                        // Calculate individual percentages
                        $educationPercent = $educationCount > 0 ? min(($educationCount / 10) * 100, 100) : 0;
                        $clinicalPercent = $clinicalCount > 0 ? min(($clinicalCount / 8) * 100, 100) : 0;
                        $researchPercent = $researchCount > 0 ? min(($researchCount / 6) * 100, 100) : 0;
                        $awardPercent = $awardCount > 0 ? min(($awardCount / 5) * 100, 100) : 0;
                    @endphp
                    <span class="badge-custom">{{ $completeness }}% Complete</span>
                </div>

                <div class="progress mt-3" style="height: 15px;">
                    <div class="progress-bar bg-success" style="width: {{ $completeness }}%"></div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-3">
                        <div class="completeness-box">
                            <h4 class="text-primary">{{ $educationCount }}</h4>
                            <small>Education</small>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: {{ $educationPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="completeness-box">
                            <h4 class="text-info">{{ $clinicalCount }}</h4>
                            <small>Clinical</small>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-info" style="width: {{ $clinicalPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="completeness-box">
                            <h4 class="text-warning">{{ $researchCount }}</h4>
                            <small>Research</small>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-warning" style="width: {{ $researchPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="completeness-box">
                            <h4 class="text-success">{{ $awardCount }}</h4>
                            <small>Awards</small>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" style="width: {{ $awardPercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Recent CVs</h6>
                    <a href="{{ route('cv.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                
                <div class="row mt-3">
                    @forelse(auth()->user()->cvs->take(2) as $cv)
                        <div class="col-md-12">
                            <div class="cv-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $cv->title }}</h6>
                                        <small class="text-muted">{{ $cv->primary_speciality ?? 'General' }}</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('cv.edit', $cv->id) }}">Edit</a></li>
                                            <li><a class="dropdown-item" href="{{ route('cv.preview', $cv->id) }}" target="_blank">Preview</a></li>
                                            <li><a class="dropdown-item" href="{{ route('cv.pdf', $cv->id) }}">Download PDF</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    @if($cv->is_public)
                                        <span class="badge bg-success">Public</span>
                                    @else
                                        <span class="badge bg-secondary">Private</span>
                                    @endif
                                    <small class="text-muted float-end">{{ $cv->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <p class="text-muted">No CVs created yet.</p>
                            <a href="{{ route('cv.create') }}" class="btn btn-primary">Create Your First CV</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <h5 class="mt-4 section-title">Quick Actions</h5>
    <div class="row">
        @php
            $quickActions = [
                ['icon' => 'fas fa-graduation-cap', 'title' => 'Add Education', 'modal' => 'addEducationModal'],
                ['icon' => 'fas fa-stethoscope', 'title' => 'Add Clinical', 'modal' => 'addClinicalModal'],
                ['icon' => 'fas fa-flask', 'title' => 'Add Research', 'modal' => 'addResearchModal'],
                ['icon' => 'fas fa-award', 'title' => 'Add Award', 'modal' => 'addAwardModal'],
                ['icon' => 'fas fa-upload', 'title' => 'Upload Doc', 'modal' => 'addDocumentModal'],
                ['icon' => 'fas fa-flag', 'title' => 'Add Milestone', 'modal' => 'addMilestoneModal'],
                ['icon' => 'fas fa-palette', 'title' => 'Templates', 'url' => route('templates.index')],
                ['icon' => 'fas fa-download', 'title' => 'Export PDF', 'url' => route('cv.pdf', auth()->user()->cvs->first()->id ?? '#')],
            ];
        @endphp

        @foreach($quickActions as $action)
            <div class="col-md-3 mb-3">
                <div class="quick-box" 
                     @if(isset($action['modal']))
                         data-bs-toggle="modal" data-bs-target="#{{ $action['modal'] }}"
                     @elseif(isset($action['url']))
                         onclick="window.location.href='{{ $action['url'] }}'"
                     @endif>
                    <i class="{{ $action['icon'] }}"></i>
                    <span>{{ $action['title'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card p-3">
                <h6>Upcoming Milestones</h6>

                @forelse($milestones as $milestone)
                    <div class="milestone-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($milestone->due_date)->format('F Y') }}</strong>
                                <p class="text-muted mb-0">{{ $milestone->title }}</p>
                                @if($milestone->description)
                                    <small class="text-muted">{{ $milestone->description }}</small>
                                @endif
                            </div>
                            <span class="badge 
                                @if(\Carbon\Carbon::parse($milestone->due_date)->isFuture()) bg-warning
                                @else bg-success @endif">
                                {{ \Carbon\Carbon::parse($milestone->due_date)->isFuture() ? 'Upcoming' : 'Done' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted py-3">No upcoming milestones.</p>
                @endforelse

                <div class="text-center mt-3">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
                        <i class="fas fa-plus me-1"></i>Add Personal Milestone
                    </button>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card p-3">
                <h6>Recent Activity</h6>

                @forelse($activities as $activity)
                    <div class="activity-box">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                {{ $activity->activity }}
                                <br><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="activity-box text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <p>No recent activity</p>
                    </div>
                @endforelse

                @if($activities->count() > 0)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All Activity</a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Manage Sections --}}
    <h5 class="mt-4 section-title">Manage Sections</h5>

    <div class="row">

        <div class="col-md-3">
            <div class="manage-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                        <span>Education & Clinical</span>
                    </div>
                    <span class="badge bg-primary">{{ $educationCount + $clinicalCount }} entries</span>
                </div>
                <div class="mt-2">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addEducationModal" class="btn btn-sm btn-outline-primary">Add Education</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addClinicalModal" class="btn btn-sm btn-outline-info">Add Clinical</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="manage-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-flask text-warning me-2"></i>
                        <span>Research & Awards</span>
                    </div>
                    <span class="badge bg-warning">{{ $researchCount + $awardCount }} entries</span>
                </div>
                <div class="mt-2">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addResearchModal" class="btn btn-sm btn-outline-warning">Add Research</a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addAwardModal" class="btn btn-sm btn-outline-success">Add Award</a>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-4">
            <div class="manage-box bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-comments me-2"></i>
                        <span>Mentor Feedback</span>
                    </div>
                    <span class="badge bg-light text-dark">3 unread</span>
                </div>
                <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-light">View Comments</a>
                </div>
            </div>
        </div> -->

        <div class="col-md-3">
            <div class="manage-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-pdf text-danger me-2"></i>
                        <span>Documents</span>
                    </div>
                    <span class="badge bg-danger">{{ $documentsCount }} files</span>
                </div>
                <div class="mt-2">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addDocumentModal" class="btn btn-sm btn-outline-danger">Upload Document</a>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-4">
            <div class="manage-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-palette text-success me-2"></i>
                        <span>CV Templates</span>
                    </div>
                    <span class="badge bg-success">{{ $cvCount }} configs</span>
                </div>
                <div class="mt-2">
                    <a href="{{ route('templates.index') }}" class="btn btn-sm btn-outline-success">Browse Templates</a>
                </div>
            </div>
        </div> -->

        <div class="col-md-3">
            <div class="manage-box">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-share-alt text-purple me-2"></i>
                        <span>Share CV</span>
                    </div>
                    <span class="badge bg-purple">Public Link</span>
                </div>
                <div class="mt-2">
                    @php
                        $firstCv = auth()->user()->cvs->first();
                    @endphp
                    @if($firstCv)
                        <a href="{{ route('cv.share', $firstCv->id) }}" class="btn btn-sm btn-outline-purple" onclick="event.preventDefault(); document.getElementById('share-form').submit();">
                            Generate Link
                        </a>
                        <form id="share-form" action="{{ route('cv.share', $firstCv->id) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>


</div>

<!-- Include Modals -->
@include('cvbuilder.modals.education-modal')
@include('cvbuilder.modals.clinical-modal')
@include('cvbuilder.modals.research-modal')
@include('cvbuilder.modals.award-modal')
@include('cvbuilder.modals.document-modal')
@include('cvbuilder.modals.milestone-modal')

@endsection