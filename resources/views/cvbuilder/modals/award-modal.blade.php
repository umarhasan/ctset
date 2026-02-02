<div class="modal fade" id="addAwardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Award or Honor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('research.store') }}" id="awardForm">
                @csrf
                <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="awardCvId">
                <input type="hidden" name="type" value="award">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Award Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="e.g., Alpha Omega Alpha Honor Medical Society">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Year Received *</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year >= 1970; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Awarding Organization</label>
                            <input type="text" class="form-control" 
                                   placeholder="e.g., American Medical Association">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="details" class="form-control" rows="3" 
                                  placeholder="Brief description, significance, selection criteria..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="competitiveAward">
                            <label class="form-check-label" for="competitiveAward">
                                Competitive award (selected from pool of candidates)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="nationalAward">
                            <label class="form-check-label" for="nationalAward">
                                National/International recognition
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Award</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset form when modal is closed
        const awardModal = document.getElementById('addAwardModal');
        awardModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('awardForm').reset();
        });
        
        // Set CV ID if not already set
        if(document.getElementById('awardCvId') && !document.getElementById('awardCvId').value) {
            const cvId = {{ $cv->id ?? 'null' }};
            if(cvId) {
                document.getElementById('awardCvId').value = cvId;
            }
        }
    });
</script>