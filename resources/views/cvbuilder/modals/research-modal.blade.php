<div class="modal fade" id="addResearchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Add Research Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('research.store') }}" id="researchForm">
                @csrf
                <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="researchCvId">
                <input type="hidden" name="type" value="research">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Research Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="e.g., Effects of Drug X on Hypertension">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Year *</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year >= 1970; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="researchStatus">
                                <option>Ongoing</option>
                                <option>Completed</option>
                                <option>Published</option>
                                <option>Presented</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Details</label>
                        <textarea name="details" class="form-control" rows="4" 
                                  placeholder="Describe the research objectives, methodology, findings, publications, presentations..."></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" placeholder="e.g., Principal Investigator">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Publication/Conference</label>
                            <input type="text" class="form-control" placeholder="e.g., Journal of Medicine">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white">Save Research</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset form when modal is closed
        const researchModal = document.getElementById('addResearchModal');
        researchModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('researchForm').reset();
        });
        
        // Set CV ID if not already set
        if(document.getElementById('researchCvId') && !document.getElementById('researchCvId').value) {
            const cvId = {{ $cv->id ?? 'null' }};
            if(cvId) {
                document.getElementById('researchCvId').value = cvId;
            }
        }
    });
</script>