{{-- resources/views/evaluation-360/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- HEADER --}}
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="fw-bold text-primary mb-3">360 EVALUATION FORM</h2>
                    <h6 class="mt-3 text-dark">{{ $form->title ?? 'Surgical Skills Assessment' }}</h6>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-{{ $share->status == 'A' ? 'success' : 'warning' }} px-4 py-2 fs-6">
                        {{ strtoupper($share->status ?? 'PENDING') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- INFO GRID --}}
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 border-end">
                    <div class="mb-3">
                        <span class="fw-bold">SHARED BY:</span>
                        <span class="ms-2 text-primary">{{ $share->sharedBy->name ?? 'Admin' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">STATUS:</span>
                        <span class="badge bg-{{ $share->status == 'A' ? 'success' : 'warning' }} ms-2">
                            {{ strtoupper($share->status ?? 'PENDING') }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">ROTATION:</span>
                        <span class="ms-2">{{ $share->rotation ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">GRADE:</span>
                        <span class="ms-2 fw-bold text-{{ $grade == 'F' ? 'danger' : 'success' }}">
                            {{ $grade ?? $share->grade ?? 'D' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">SCORE:</span>
                        <span class="ms-2 fw-bold text-primary">{{ $percentage ?? 0 }}%</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="fw-bold">NAME:</span>
                        <span class="ms-2">{{ $share->assignedTo->name ?? 'MUHAMMAD RASHID' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">EMAIL:</span>
                        <span class="ms-2">{{ $share->assignedTo->email ?? 'mrashid@example.com' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">PHONE:</span>
                        <span class="ms-2">{{ $share->assignedTo->phone ?? '03333562634' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- SECTIONS LOOP --}}
    @forelse($form->sections as $section)
    
    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-header bg-light py-3 rounded-top">
            <h5 class="mb-0 fw-bold">{{ $section->section_title }}</h5>
        </div>
        
        @php
            // IS SECTION KA RESPONSE GET KARO
            $response = $share->responses->where('section_id', $section->id)->first();
            $selectedScore = $response->score ?? null;
            $comment = $response->comments ?? '';
            $ueChecked = $response->unable_to_evaluate ?? false;
        @endphp
        
        <div class="card-body p-4">
            {{-- QUESTION HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">{{ $section->subtitle ?: $section->section_title }}</h6>
                <span class="badge bg-primary px-4 py-2 rounded-pill">
                    Score: {{ $section->score ?? 0 }}%
                </span>
            </div>
            
            {{-- RATING TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <tbody>
                        <tr>
                            <td class="fw-bold bg-light">{{ $section->subtitle ?: 'Rating' }}</td>
                            
                            {{-- 1-7 RATING RADIOS --}}
                            @for($i = 1; $i <= 7; $i++)
                            <td>
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input rating-radio" 
                                           type="radio" 
                                           name="responses[{{ $section->id }}][score]" 
                                           value="{{ $i }}"
                                           id="rating_{{ $section->id }}_{{ $i }}"
                                           {{ $selectedScore == $i ? 'checked' : '' }}
                                           {{ $share->status == 'A' ? 'disabled' : '' }}
                                           {{ $ueChecked ? 'disabled' : '' }}>
                                    <label class="form-check-label ms-1" for="rating_{{ $section->id }}_{{ $i }}">
                                        {{ $i }}
                                    </label>
                                </div>
                            </td>
                            @endfor
                            
                            {{-- UE CHECKBOX --}}
                            <td style="background: #fff3cd;">
                                <div class="form-check d-flex justify-content-center align-items-center">
                                    <input class="form-check-input ue-checkbox" 
                                           type="checkbox" 
                                           id="ue_{{ $section->id }}"
                                           data-section="{{ $section->id }}"
                                           {{ $ueChecked ? 'checked' : '' }}
                                           {{ $share->status == 'A' ? 'disabled' : '' }}>
                                    <label class="form-check-label ms-2 fw-bold text-danger" for="ue_{{ $section->id }}">
                                        UE
                                    </label>
                                </div>
                                <input type="hidden" 
                                       name="responses[{{ $section->id }}][unable_to_evaluate]" 
                                       id="ue_hidden_{{ $section->id }}"
                                       value="{{ $ueChecked ? '1' : '0' }}">
                            </td>
                        </tr>
                        <tr class="bg-light">
                            <td class="fw-bold">Description</td>
                            <td colspan="4" class="text-start text-white" style="background: #dc3545;">
                                <span class="ms-2">{{ $section->col_1_5 ?: 'Poor performance' }}</span>
                            </td>
                            <td colspan="3" class="text-start text-white" style="background: #28a745;">
                                <span class="ms-2">{{ $section->col_6_7 ?: 'Excellent performance' }}</span>
                            </td>
                            <td class="text-start" style="background: #6c757d; color: white;">
                                <span class="ms-2">{{ $section->ue ?: 'Unable to evaluate' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            {{-- COMMENTS --}}
            <div class="mt-3 p-3 bg-light rounded">
                <div class="fw-bold mb-2">COMMENTS:</div>
                @if($share->status == 'A' || $comment)
                    <p class="mb-0">{{ $comment ?: 'No comments added' }}</p>
                @else
                    <textarea class="form-control" 
                              name="responses[{{ $section->id }}][comment]" 
                              rows="2" 
                              placeholder="Add your comments here...">{{ $comment }}</textarea>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-warning">
        No sections found for this form.
    </div>
    @endforelse
    
    {{-- SUBMIT BUTTON --}}
    @if($share->status != 'A')
    <div class="text-center mt-4 mb-5">
        <button type="submit" form="evaluationForm" class="btn btn-primary btn-lg px-5 rounded-pill" disabled>
            <i class="fas fa-paper-plane me-2"></i> SUBMIT EVALUATION
        </button>
        <button type="button" class="btn btn-outline-secondary btn-lg px-5 rounded-pill ms-3" id="resetBtn">
            <i class="fas fa-undo me-2"></i> RESET
        </button>
    </div>
    @else
    <div class="text-center mt-4 mb-5">
        <div class="alert alert-success d-inline-block px-5 py-3 rounded-pill">
            <i class="fas fa-check-circle me-2"></i> EVALUATION COMPLETED
        </div>
    </div>
    @endif
    
</div>

<form id="evaluationForm" action="{{ route('evaluation-360.update', $share->id ?? '') }}" method="POST">
    @csrf
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // UE CHECKBOX HANDLER
    const ueCheckboxes = document.querySelectorAll('.ue-checkbox');
    
    ueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const sectionId = this.dataset.section;
            const radios = document.querySelectorAll(`input[name="responses[${sectionId}][score]"]`);
            const hiddenUe = document.getElementById(`ue_hidden_${sectionId}`);
            
            if (this.checked) {
                // Disable all radios
                radios.forEach(radio => {
                    radio.disabled = true;
                    radio.checked = false;
                    radio.closest('td').style.opacity = '0.5';
                });
                // Set hidden input value
                if (hiddenUe) hiddenUe.value = '1';
            } else {
                // Enable all radios
                radios.forEach(radio => {
                    radio.disabled = false;
                    radio.closest('td').style.opacity = '1';
                });
                // Set hidden input value
                if (hiddenUe) hiddenUe.value = '0';
            }
        });
    });
    
    // RADIO BUTTON HANDLER
    const ratingRadios = document.querySelectorAll('.rating-radio');
    
    ratingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const sectionId = this.name.match(/\d+/)[0];
            const ueCheckbox = document.getElementById(`ue_${sectionId}`);
            const hiddenUe = document.getElementById(`ue_hidden_${sectionId}`);
            
            if (ueCheckbox) {
                ueCheckbox.checked = false;
                if (hiddenUe) hiddenUe.value = '0';
            }
        });
    });
    
    // RESET BUTTON
    const resetBtn = document.getElementById('resetBtn');
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to reset all ratings?')) {
                const allRadios = document.querySelectorAll('.rating-radio');
                const allUeCheckboxes = document.querySelectorAll('.ue-checkbox');
                const allTextareas = document.querySelectorAll('textarea');
                
                allRadios.forEach(radio => {
                    radio.checked = false;
                    radio.disabled = false;
                    radio.closest('td').style.opacity = '1';
                });
                
                allUeCheckboxes.forEach(cb => {
                    cb.checked = false;
                    const sectionId = cb.dataset.section;
                    const hiddenUe = document.getElementById(`ue_hidden_${sectionId}`);
                    if (hiddenUe) hiddenUe.value = '0';
                });
                
                allTextareas.forEach(textarea => {
                    textarea.value = '';
                });
            }
        });
    }
});
</script>
@endpush