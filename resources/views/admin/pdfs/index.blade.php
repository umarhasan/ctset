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
            <thead>
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

                    <td><input class="form-control" name="pdfs[{{ $i }}][page_name]" value="{{ $pdf->page_name }}"></td>
                    <td><input class="form-control" name="pdfs[{{ $i }}][page_key]" value="{{ $pdf->page_key }}"></td>
                    <td><input class="form-control" name="pdfs[{{ $i }}][title]" value="{{ $pdf->title }}"></td>
                    <td><input type="file" name="pdfs[{{ $i }}][file]"></td>
                    <td class="text-center">
                        @if($pdf->file)
                            <a href="{{ asset('storage/'.$pdf->file) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                        @else
                            —
                        @endif
                    </td>
                    <td><input class="form-control" name="pdfs[{{ $i }}][total_pages]" value="{{ $pdf->total_pages }}"></td>
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

<script>
let i = {{ $pdfs->count() }};

function addRow() {
    document.getElementById('rows').insertAdjacentHTML('beforeend', `
        <tr>
            <td><input class="form-control" name="pdfs[${i}][page_name]"></td>
            <td><input class="form-control" name="pdfs[${i}][page_key]"></td>
            <td><input class="form-control" name="pdfs[${i}][title]"></td>
            <td><input type="file" name="pdfs[${i}][file]"></td>
            <td>—</td>
            <td><input class="form-control" name="pdfs[${i}][total_pages]"></td>
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
                if(res.success){
                    button.closest('tr').remove();
                }
            });
        }
    } else {
        button.closest('tr').remove();
    }
}
</script>
@endsection
