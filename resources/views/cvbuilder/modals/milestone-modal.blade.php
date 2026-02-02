<div class="modal fade" id="addMilestoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title">Add Milestone</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('milestone.store') }}" id="milestoneForm">
                @csrf
                <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="milestoneCvId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Milestone Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="e.g., USMLE Step 1, Residency Interview">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Month *</label>
                            <select class="form-select" id="milestoneMonth" required>
                                <option value="">Select Month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Year *</label>
                            <select class="form-select" id="milestoneYear" required>
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year <= date('Y') + 5; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" name="month_year" id="monthYearInput">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Milestone Type</label>
                        <select class="form-select" id="milestoneType">
                            <option value="exam">Exam/Test</option>
                            <option value="application">Application</option>
                            <option value="interview">Interview</option>
                            <option value="deadline">Deadline</option>
                            <option value="event">Event</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Details about this milestone, preparation needed, importance..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="importantMilestone">
                            <label class="form-check-label" for="importantMilestone">
                                Important milestone (will be highlighted)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="completedMilestone">
                            <label class="form-check-label" for="completedMilestone">
                                Already completed
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-purple text-white">Save Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Combine month and year for submission
        const monthSelect = document.getElementById('milestoneMonth');
        const yearSelect = document.getElementById('milestoneYear');
        const monthYearInput = document.getElementById('monthYearInput');
        
        function updateMonthYear() {
            const month = monthSelect.value;
            const year = yearSelect.value;
            if(month && year) {
                monthYearInput.value = `${month} ${year}`;
            }
        }
        
        monthSelect.addEventListener('change', updateMonthYear);
        yearSelect.addEventListener('change', updateMonthYear);
        
        // Reset form when modal is closed
        const milestoneModal = document.getElementById('addMilestoneModal');
        milestoneModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('milestoneForm').reset();
            monthYearInput.value = '';
        });
        
        // Set CV ID if not already set
        if(document.getElementById('milestoneCvId') && !document.getElementById('milestoneCvId').value) {
            const cvId = {{ $cv->id ?? 'null' }};
            if(cvId) {
                document.getElementById('milestoneCvId').value = cvId;
            }
        }
    });
</script>

<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
        color: white;
    }
</style>