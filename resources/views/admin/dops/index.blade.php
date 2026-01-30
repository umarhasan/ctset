@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">DOPS Management</h5>
        <!-- Add DOPS button opens modal -->
        <div class="ms-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dopsModal">
                <i class="fa fa-plus"></i> Add DOPS
            </button>
        </div>

    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered">
             <thead class="table-dark">
                <tr>
                    <th width="40">#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dops as $k => $d)
                <tr>
                    <td>{{ $k + 1 }}</td>
                    <td>{{ $d->title }}</td>
                    <td>{{ $d->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <!-- Edit goes to separate page -->
                        <a href="{{ route('dops.edit', $d->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <!-- Delete button -->
                        <form method="POST" action="{{ route('dops.destroy', $d->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= ADD DOPS MODAL ================= --}}
<div class="modal fade" id="dopsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form method="POST" id="dopsForm" action="{{ route('dops.store') }}">
            @csrf
            <input type="hidden" name="level_html" id="level_html">
            <input type="hidden" name="rotation_html" id="rotation_html">
            <input type="hidden" name="rating_html" id="rating_html">
            <input type="hidden" name="competency_html" id="competency_html">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="mb-0">Add DOPS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- TITLE --}}
                    <div class="mb-2">
                        <label class="form-label">Title</label>
                        <input name="title" id="title" class="form-control" required>
                    </div>

                    {{-- ROTATION --}}
                    <div class="mb-2">
                        <label class="form-label">Rotation</label>
                        <select name="rotation_ids[]" id="rotation_ids" multiple class="form-control">
                            @foreach($rotations as $r)
                                <option value="{{ $r->id }}">{{ $r->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- LEVEL --}}
                    <div class="mb-2">
                        <label class="form-label">Level</label>
                        <select name="level_ids[]" id="level_ids" multiple class="form-control">
                            @foreach($levels as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- RATING --}}
                    <div class="mb-2">
                        <label class="form-label">Rating</label>
                        <select name="rating_ids[]" id="rating_ids" multiple class="form-control">
                            @foreach($ratings as $r)
                                <option value="{{ $r->id }}">{{ $r->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- STEPS --}}
                    <div class="mb-2">
                        <label class="form-label">Steps</label>
                        <textarea name="steps" id="steps"></textarea>
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
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Summernote CSS/JS -->
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

let rowIndex = 0;

/* ================= CATEGORY + DEFINITION FUNCTIONS ================= */
function addCategoryRow(order='', comp_id='') {
    let html = `<tr data-index="${rowIndex}">
        <td><input type="text" class="form-control" name="competencies[${rowIndex}][order]" value="${order}" required></td>
        <td>
            <select name="competencies[${rowIndex}][competency_id]" class="form-control" required>
                <option value="">Select ...</option>
                @foreach($competencies as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
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
    rowIndex++;
}

function addDefinitionRow(index, val='') {
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

function removeDefinition(btn) {
    let wrapper = btn.closest('.def-item');
    let parent = wrapper.parentElement;
    wrapper.remove();
    if(parent.children.length===0) parent.closest('tr').style.display='none';
}

function removeCategory(index) {
    document.querySelectorAll('tr[data-index="'+index+'"], #def_row_'+index).forEach(r => r.remove());
}

/* ================= GENERATE HTML BEFORE SUBMIT ================= */
document.querySelector('#dopsForm').addEventListener('submit', function(e){
    this.querySelector('#rotation_html').value = Array.from(this.querySelectorAll('#rotation_ids option:checked')).map(o => `<li>${o.textContent}</li>`).join('');
    this.querySelector('#level_html').value = Array.from(this.querySelectorAll('#level_ids option:checked')).map(o => `<li>${o.textContent}</li>`).join('');
    this.querySelector('#rating_html').value = Array.from(this.querySelectorAll('#rating_ids option:checked')).map(o => `<li>${o.textContent}</li>`).join('');

    let compHtml = '';
    this.querySelectorAll('#competencyTable tr[data-index]').forEach(row=>{
        let index = row.dataset.index;
        let compSelect = row.querySelector('select');
        if(!compSelect) return;

        let compId = compSelect.value;
        let compName = compSelect.options[compSelect.selectedIndex]?.text || '';

        if(compId && compName){
            compHtml += `<div class="competency" data-id="${compId}"><h5>${compName}</h5><ul>`;

            this.querySelectorAll(`#def_cell_${index} input`).forEach((input, i)=>{
                let val = input.value.trim();
                if(val!==''){
                    compHtml += `<li data-index="${i+1}">${val}</li>`;
                }
            });

            compHtml += `</ul></div>`;
        }
    });
    this.querySelector('#competency_html').value = compHtml;
    // Save Summernote content
    this.querySelector('textarea[name="steps"]').value = $('#steps').summernote('code');
});
</script>
@endpush
