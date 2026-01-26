@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit DOPS</h5>
        <div class="ms-auto">
            <a href="{{ route('dops.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" id="dopsEditForm" action="{{ route('dops.update', $dops->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="level_html" id="level_html">
            <input type="hidden" name="rotation_html" id="rotation_html">
            <input type="hidden" name="rating_html" id="rating_html">
            <input type="hidden" name="competency_html" id="competency_html">

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" id="title" class="form-control" required value="{{ $dops->title }}">
            </div>

            {{-- ROTATIONS --}}
            <div class="mb-3">
                <label class="form-label">Rotation</label>
                <select name="rotation_ids[]" id="rotation_ids" multiple class="form-control">
                    @foreach($rotations as $r)
                        <option value="{{ $r->id }}" {{ in_array($r->id, $dops->rotationIds) ? 'selected' : '' }}>{{ $r->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- LEVELS --}}
            <div class="mb-3">
                <label class="form-label">Level</label>
                <select name="level_ids[]" id="level_ids" multiple class="form-control">
                    @foreach($levels as $l)
                        <option value="{{ $l->id }}" {{ in_array($l->id, $dops->levelIds) ? 'selected' : '' }}>{{ $l->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- RATINGS --}}
            <div class="mb-3">
                <label class="form-label">Rating</label>
                <select name="rating_ids[]" id="rating_ids" multiple class="form-control">
                    @foreach($ratings as $r)
                        <option value="{{ $r->id }}" {{ in_array($r->id, $dops->ratingIds) ? 'selected' : '' }}>{{ $r->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- STEPS --}}
            <div class="mb-3">
                <label class="form-label">Steps</label>
                <textarea name="steps" id="steps">{{ $dops->steps }}</textarea>
            </div>

            {{-- COMPETENCIES --}}
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Competencies & Definitions</strong>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary btn-sm" onclick="addCategoryRow()">+ Add Category</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th width="80">#</th>
                                <th>Competency</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody id="competencyTable"></tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-success">Update DOPS</button>
                <a href="{{ route('dops.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#steps').summernote({
        placeholder: 'Enter steps here...',
        tabsize: 2,
        height: 150
    });
});

// ================= CATEGORY & DEFINITIONS =================
let rowIndex = 0;

// Add new competency category row
function addCategoryRow(order='', comp_id='', definitions=[]) {
    let html = `<tr data-index="${rowIndex}">
        <td><input type="text" class="form-control" name="competencies[${rowIndex}][order]" value="${order}" required></td>
        <td>
            <select name="competencies[${rowIndex}][competency_id]" class="form-control" required>
                <option value="">Select ...</option>
                @foreach($competencies as $c)
                    <option value="{{ $c->id }}" ${comp_id == {{ $c->id }} ? 'selected' : ''}>{{ $c->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-primary" onclick="addDefinitionRow(${rowIndex})">+</button>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeCategory(${rowIndex})"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
    <tr id="def_row_${rowIndex}" style="display:none">
        <td></td>
        <td colspan="2" id="def_cell_${rowIndex}"></td>
    </tr>`;
    document.getElementById('competencyTable').insertAdjacentHTML('beforeend', html);

    // Prefill definitions if any
    if(definitions.length > 0){
        definitions.forEach(d => addDefinitionRow(rowIndex, d));
    }

    rowIndex++;
}

// Add new definition input under a category
function addDefinitionRow(index, val=''){
    let row = document.getElementById('def_row_' + index);
    row.style.display = '';
    let cell = document.getElementById('def_cell_' + index);
    cell.insertAdjacentHTML('beforeend', `
        <div class="d-flex align-items-center mb-1 def-item">
            <input type="text" name="competencies[${index}][definitions][]" class="form-control me-2" value="${val}" placeholder="Definition">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDefinition(this)"><i class="fa fa-trash"></i></button>
        </div>
    `);
}

// Remove a definition
function removeDefinition(btn){
    let wrapper = btn.closest('.def-item');
    let parent = wrapper.parentElement;
    wrapper.remove();
    if(parent.children.length===0) parent.closest('tr').style.display='none';
}

// Remove category
function removeCategory(index){
    document.querySelectorAll('tr[data-index="'+index+'"], #def_row_'+index).forEach(r => r.remove());
}

// ================= GENERATE HTML BEFORE SUBMIT =================
document.querySelector('#dopsEditForm').addEventListener('submit', function(e){
    this.querySelector('#rotation_html').value = Array.from(this.querySelectorAll('#rotation_ids option:checked')).map(o=>`<li>${o.textContent}</li>`).join('');
    this.querySelector('#level_html').value = Array.from(this.querySelectorAll('#level_ids option:checked')).map(o=>`<li>${o.textContent}</li>`).join('');
    this.querySelector('#rating_html').value = Array.from(this.querySelectorAll('#rating_ids option:checked')).map(o=>`<li>${o.textContent}</li>`).join('');

    let compHtml = '';
    this.querySelectorAll('#competencyTable tr[data-index]').forEach(row=>{
        let index = row.dataset.index;
        let compName = row.querySelector('select option:checked')?.textContent || '';
        if(compName){
            compHtml += `<h5>${compName}</h5><ul>`;
            this.querySelectorAll(`#def_cell_${index} input`).forEach(input=>{
                if(input.value.trim()!=='') compHtml += `<li>${input.value.trim()}</li>`;
            });
            compHtml += '</ul>';
        }
    });
    this.querySelector('#competency_html').value = compHtml;
    this.querySelector('textarea[name="steps"]').value = $('#steps').summernote('code');
});

// ================= PREFILL EXISTING DATA =================
let existingData = @json($dops->competenciesArray);

existingData.forEach(c => {
    addCategoryRow(c.order, c.competency_id, c.definitions);
});
</script>
@endpush
