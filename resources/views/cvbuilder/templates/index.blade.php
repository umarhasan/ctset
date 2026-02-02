@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>CV Templates</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
            + Add Template
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($templates as $template)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $template->name }}</h5>
                        @if($template->is_default)
                            <span class="badge bg-primary mb-2">Default</span>
                        @endif
                        <div class="flex-grow-1 mb-2" style="overflow:hidden; max-height:120px;">
                            {!! $template->design_html !!}
                        </div>
                        <div class="mt-auto d-flex justify-content-between flex-wrap gap-1">
                            <!-- Edit -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTemplateModal{{ $template->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <!-- Delete -->
                            <form action="{{ route('templates.destroy', $template) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>

                            <!-- Set Default -->
                            @if(!$template->is_default)
                                <form action="{{ route('templates.setDefault', $template) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i> Set Default
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editTemplateModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <form action="{{ route('templates.update', $template) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Template</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body d-flex gap-3">
                            <div class="flex-grow-1">
                                <div class="mb-3">
                                    <label class="form-label">Template Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $template->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Design HTML</label>
                                    <textarea class="form-control editDesignHtml" name="design_html" rows="15" required>{{ $template->design_html }}</textarea>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" {{ $template->is_default ? 'checked' : '' }}>
                                    <label class="form-check-label">Set as Default</label>
                                </div>
                            </div>

                            <!-- Live Preview -->
                            <div class="flex-grow-1 border p-3" style="max-height:600px; overflow:auto; background:#f8f9fa;">
                                <h5>Live Preview</h5>
                                <div class="editTemplatePreview" style="min-height:400px;">{!! $template->design_html !!}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Update Template</button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Add Template Modal -->
<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form action="{{ route('templates.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex gap-3">
                <div class="flex-grow-1">
                    <div class="mb-3">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Design HTML</label>
                        <textarea class="form-control" id="addDesignHtml" name="design_html" rows="15" required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1">
                        <label class="form-check-label">Set as Default</label>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="flex-grow-1 border p-3" style="max-height:600px; overflow:auto; background:#f8f9fa;">
                    <h5>Live Preview</h5>
                    <div id="addTemplatePreview" style="min-height:400px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save Template</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
    // Add Template live preview
    document.getElementById('addDesignHtml').addEventListener('input', function() {
        document.getElementById('addTemplatePreview').innerHTML = this.value;
    });

    // Edit Template live preview
    document.querySelectorAll('.editDesignHtml').forEach((textarea, index) => {
        textarea.addEventListener('input', function() {
            document.querySelectorAll('.editTemplatePreview')[index].innerHTML = this.value;
        });
    });
</script>
@endsection
