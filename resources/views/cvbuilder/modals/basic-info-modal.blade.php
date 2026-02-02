<div class="modal fade" id="editBasicInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Basic Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('cv.update', $cv->id) }}" id="basicInfoForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">CV Title *</label>
                        <input type="text" name="title" class="form-control" required 
                               value="{{ $cv->title }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Primary Speciality</label>
                        <input type="text" name="primary_speciality" class="form-control" 
                               value="{{ $cv->primary_speciality }}" 
                               placeholder="e.g., Internal Medicine">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Professional Summary</label>
                        <textarea name="summary" class="form-control" rows="4">{{ $cv->summary }}</textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Template</label>
                            <select name="template" class="form-select">
                                <option value="template1" {{ $cv->template == 'template1' ? 'selected' : '' }}>Modern Professional</option>
                                <option value="template2" {{ $cv->template == 'template2' ? 'selected' : '' }}>Academic Focus</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_public" 
                                       id="is_public" {{ $cv->is_public ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">Make CV Public</label>
                            </div>
                            <small class="text-muted">Public CVs can be accessed via shareable link</small>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>