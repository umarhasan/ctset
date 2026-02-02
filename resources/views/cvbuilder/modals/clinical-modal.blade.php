<div class="modal fade" id="addClinicalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Add Clinical Experience</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('education.store') }}" id="clinicalForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="clinicalMethod">
                <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="clinicalCvId">
                <input type="hidden" name="type" value="clinical">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Position/Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="e.g., Internal Medicine Clerkship">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Hospital/Institution *</label>
                        <input type="text" name="institute" class="form-control" required 
                               placeholder="e.g., Massachusetts General Hospital">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Year *</label>
                            <select name="year_from" class="form-select" required>
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year >= 1970; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Year</label>
                            <select name="year_to" class="form-select">
                                <option value="">Present</option>
                                @for($year = date('Y'); $year >= 1970; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Responsibilities & Achievements</label>
                        <textarea name="details" class="form-control" rows="4" 
                                  placeholder="Describe your responsibilities, skills developed, patient interactions, procedures performed..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white">Save Clinical Experience</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset form when modal is closed
        const clinicalModal = document.getElementById('addClinicalModal');
        clinicalModal.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('clinicalForm');
            form.reset();
            form.action = '{{ route("education.store") }}';
            form.querySelector('#clinicalMethod').value = 'POST';
        });
        
        // Set CV ID if not already set
        if(document.getElementById('clinicalCvId') && !document.getElementById('clinicalCvId').value) {
            const cvId = {{ $cv->id ?? 'null' }};
            if(cvId) {
                document.getElementById('clinicalCvId').value = cvId;
            }
        }
    });
</script>