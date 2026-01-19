@extends('layouts.trainee')

@section('content')
<div class="container-fluid">
    <h3>{{ $page_name }}</h3>
    <span>Page <span id="page-num">{{ $file }}</span> of <span id="page-count">{{ $totalPages }}</span></span>

    <div class="my-2">
        <button class="btn btn-secondary" onclick="prevPage()">Prev</button>
        <input type="number" id="go-to-page" value="{{ $file }}" min="1" max="{{ $totalPages }}" style="width: 50px;">
        <button class="btn btn-secondary" onclick="nextPage()">Next</button>
    </div>

    <canvas id="pdf-render" style="border:1px solid #ccc; width:100%; height:600px;"></canvas>

    <input type="hidden" id="pdf-path" value="{{ asset('storage/'.$pdfBasePath) }}">
    <input type="hidden" id="total-pages" value="{{ $totalPages }}">
    <input type="hidden" id="current-page" value="{{ $file }}">
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfPath = $('#pdf-path').val() + $('#current-page').val() + '.pdf';
let totalPages = parseInt($('#total-pages').val());
let currentPage = parseInt($('#current-page').val());

function renderPage(pageNum) {
    const canvas = document.getElementById('pdf-render');
    const ctx = canvas.getContext('2d');
    pdfjsLib.getDocument(pdfPath).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: 1.5 });
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            page.render({ canvasContext: ctx, viewport: viewport });
        });
    });
}

function nextPage() {
    if(currentPage < totalPages) currentPage++;
    window.location.href = "{{ route('trainee.pdfs.show', ['page_name'=>$page_name]) }}/" + currentPage;
}

function prevPage() {
    if(currentPage > 1) currentPage--;
    window.location.href = "{{ route('trainee.pdfs.show', ['page_name'=>$page_name]) }}/" + currentPage;
}

$('#go-to-page').on('blur', function() {
    let page = parseInt($(this).val());
    if(page >= 1 && page <= totalPages){
        window.location.href = "{{ route('trainee.pdfs.show', ['page_name'=>$page_name]) }}/" + page;
    }
});

// Initial render
renderPage(currentPage);
</script>
@endpush
