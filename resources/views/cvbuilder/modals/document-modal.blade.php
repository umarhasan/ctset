<!-- Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="documentModalTitle">Upload Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
           <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data" id="documentForm">
            @csrf
            <input type="hidden" name="cv_id" value="{{ $cv->id ?? '' }}" id="documentCvId">
            <input type="hidden" name="document_id" id="documentId"> <!-- for edit -->
            <input type="hidden" name="_method" id="documentMethod"> <!-- dynamically set to PUT for edit --> <!-- for edit -->

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Title *</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g., Medical License, Publication PDF, Certificate">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Document Type</label>
                        <select class="form-select" name="document_type">
                            <option value="">Select Type</option>
                            <option value="certificate">Certificate</option>
                            <option value="license">License</option>
                            <option value="publication">Publication</option>
                            <option value="transcript">Transcript</option>
                            <option value="letter">Letter of Recommendation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Choose File *</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text">
                            Supported formats: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mb-3" id="documentPreviewContainer" style="display:none;">
                        <label class="form-label">Preview:</label>
                        <div id="documentPreview"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Additional notes about this document"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Documents will be stored securely and can be downloaded or shared as needed.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="documentSubmitBtn">Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const documentModal = document.getElementById('addDocumentModal');
    const documentForm = document.getElementById('documentForm');
    const fileInput = documentForm.querySelector('input[name="file"]');
    const previewContainer = document.getElementById('documentPreviewContainer');
    const previewDiv = document.getElementById('documentPreview');
    const documentMethod = documentForm.querySelector('#documentMethod');

    // Reset modal on close
    documentModal.addEventListener('hidden.bs.modal', function () {
        documentForm.reset();
        previewDiv.innerHTML = '';
        previewContainer.style.display = 'none';
        documentForm.action = "{{ route('document.store') }}";
        documentMethod.value = '';
        documentModal.querySelector('#documentModalTitle').textContent = 'Upload Document';
        documentForm.querySelector('#documentSubmitBtn').textContent = 'Upload Document';
    });

    // File preview
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        previewDiv.innerHTML = '';
        if(!file) return;

        const maxSize = 5 * 1024 * 1024;
        if(file.size > maxSize){
            alert('File size exceeds 5MB.');
            this.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        previewContainer.style.display = 'block';
        const ext = file.name.split('.').pop().toLowerCase();

        if(['jpg','jpeg','png'].includes(ext)){
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxHeight = '120px';
            img.style.borderRadius = '5px';
            previewDiv.appendChild(img);
        } else {
            const p = document.createElement('p');
            p.textContent = file.name;
            previewDiv.appendChild(p);
        }
    });

    // Edit document button
    document.querySelectorAll('.edit-document').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const title = this.dataset.title;
            const file = this.dataset.file;

            documentModal.querySelector('#documentModalTitle').textContent = 'Edit Document';
            documentForm.querySelector('[name="title"]').value = title;
            documentForm.querySelector('#documentId').value = id;
            documentForm.querySelector('#documentSubmitBtn').textContent = 'Update Document';

            // Set PUT method for edit
            documentMethod.value = 'PUT';

            // Correct action URL for resource route with cvbuilder prefix
            documentForm.action = "{{ url('cvbuilder/document') }}/" + id;

            // Preview existing file
            previewDiv.innerHTML = '';
            previewContainer.style.display = 'block';
            const ext = file.split('.').pop().toLowerCase();

            if(['jpg','jpeg','png'].includes(ext)){
                const img = document.createElement('img');
                img.src = "{{ route('cv.document.stream', '') }}/" + file;
                img.style.maxHeight = '120px';
                img.style.borderRadius = '5px';
                previewDiv.appendChild(img);
            } else {
                const a = document.createElement('a');
                a.href = "{{ route('cv.document.stream', '') }}/" + file;
                a.target = "_blank";
                a.textContent = file;
                previewDiv.appendChild(a);
            }

            const modal = new bootstrap.Modal(documentModal);
            modal.show();
        });
    });
});

</script>
