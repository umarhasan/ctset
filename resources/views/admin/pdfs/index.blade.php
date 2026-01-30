@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('pdfs.store') }}" enctype="multipart/form-data">
@csrf

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>PDF Management (Create / Update / Delete)</h4>
        <button class="btn btn-success btn-sm">Save All</button>
    </div>

    <div class="card-body">
        <table class="table table-bordered align-middle">
             <thead class="table-dark">
                <tr>
                    <th>Dropdown Name</th>
                    <th>Permission Key</th>
                    <th>Title</th>
                    <th>Upload PDF</th>
                    <th>View</th>
                    <th>Pages</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="rows">
                @foreach($pdfs as $i => $pdf)
                <tr>
                    <input type="hidden" name="pdfs[{{ $i }}][id]" value="{{ $pdf->id }}">

                    <td>
                        <select class="form-control" name="pdfs[{{ $i }}][page_name]">
                            <option value="Academic Input" @selected($pdf->page_name=="Academic Input")>Academic Input</option>
                            <option value="Clinical Rotations" @selected($pdf->page_name=="Clinical Rotations")>Clinical Rotations</option>
                        </select>
                    </td>

                    <td>
                        <input class="form-control permission-key" name="pdfs[{{ $i }}][page_key]" value="{{ $pdf->page_key }}" readonly>
                    </td>

                    <td>
                        <input class="form-control title-input" name="pdfs[{{ $i }}][title]" value="{{ $pdf->title }}">
                    </td>

                    <td>
                        <input type="file" class="pdf-upload" name="pdfs[{{ $i }}][file]">
                    </td>

                    <td class="text-center">
                        @if($pdf->file)
                            <a href="{{ route('pdfs.stream', basename($pdf->file)) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                        @else — @endif
                    </td>

                    <td>
                        <input class="form-control total-pages" name="pdfs[{{ $i }}][total_pages]" value="{{ $pdf->total_pages }}" readonly>
                    </td>

                    <td>
                        <select class="form-control" name="pdfs[{{ $i }}][status]">
                            <option value="1" @selected($pdf->status)>Active</option>
                            <option value="0" @selected(!$pdf->status)>Inactive</option>
                        </select>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this, {{ $pdf->id ?? 0 }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-primary btn-sm" onclick="addRow()">+ Add New PDF</button>
    </div>
</div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let i = {{ $pdfs->count() }};

// Add new row
function addRow() {
    document.getElementById('rows').insertAdjacentHTML('beforeend', `
        <tr>
            <td>
                <select class="form-control" name="pdfs[${i}][page_name]">
                    <option value="Academic Input">Academic Input</option>
                    <option value="Clinical Rotations">Clinical Rotations</option>
                </select>
            </td>
            <td><input class="form-control permission-key" name="pdfs[${i}][page_key]" readonly></td>
            <td><input class="form-control title-input" name="pdfs[${i}][title]"></td>
            <td><input type="file" class="pdf-upload" name="pdfs[${i}][file]"></td>
            <td>—</td>
            <td><input class="form-control total-pages" name="pdfs[${i}][total_pages]" readonly></td>
            <td>
                <select class="form-control" name="pdfs[${i}][status]">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this, 0)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `);
    i++;
}

// Delete row
function deleteRow(button, id){
    if(id != 0){
        if(confirm('Are you sure to delete?')){
            fetch(`pdfs/${id}`,{
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Accept':'application/json'
                }
            }).then(res=>res.json()).then(res=>{
                if(res.success) button.closest('tr').remove();
            });
        }
    } else {
        button.closest('tr').remove();
    }
}

// Generate Permission Key: first letters of first 2 words + _pdf
document.addEventListener('input', function(e){
    if(e.target.classList.contains('title-input')){
        const keyInput = e.target.closest('tr').querySelector('.permission-key');
        const words = e.target.value.trim().split(/\s+/);
        let key = '';
        if(words.length >= 2){
            key = words[0][0].toUpperCase() + words[1][0].toUpperCase() + '_pdf';
        } else if(words.length == 1){
            key = words[0][0].toUpperCase() + '_pdf';
        }
        keyInput.value = key;
    }
});

// Auto get total pages from uploaded PDF
document.addEventListener('change', function(e){
    if(e.target.classList.contains('pdf-upload')){
        const fileInput = e.target;
        const totalPagesInput = e.target.closest('tr').querySelector('.total-pages');
        const file = fileInput.files[0];
        if(file && file.type === 'application/pdf'){
            const reader = new FileReader();
            reader.onload = function() {
                const typedarray = new Uint8Array(this.result);
                pdfjsLib.getDocument(typedarray).promise.then(pdf=>{
                    totalPagesInput.value = pdf.numPages;
                });
            };
            reader.readAsArrayBuffer(file);
        }
    }
});
</script>
@endsection
