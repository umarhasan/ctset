<div class="modal fade" id="addEducationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Education</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('education.store') }}" id="educationForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="educationMethod">
                <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="educationCvId">
                <input type="hidden" name="type" value="education">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Degree/Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="e.g., Doctor of Medicine (MD)">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Institution *</label>
                        <input type="text" name="institute" class="form-control" required 
                               placeholder="e.g., Harvard Medical School">
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
                        <label class="form-label">Details</label>
                        <textarea name="details" class="form-control" rows="3" 
                                  placeholder="Additional details, achievements, GPA, honors..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Education</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset form when modal is closed
        const educationModal = document.getElementById('addEducationModal');
        educationModal.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('educationForm');
            form.reset();
            form.action = '{{ route("education.store") }}';
            form.querySelector('#educationMethod').value = 'POST';
        });
        
        // Set CV ID if not already set
        if(document.getElementById('educationCvId') && !document.getElementById('educationCvId').value) {
            const cvId = {{ $cv->id ?? 'null' }};
            if(cvId) {
                document.getElementById('educationCvId').value = cvId;
            }
        }
    });
</script>