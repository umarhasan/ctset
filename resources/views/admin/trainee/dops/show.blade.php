@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm border-0">
        <div class="card-body p-2">
            {{-- Top Row --}}
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">{{ $page_name }}</h5>
                    <span class="badge bg-secondary" id="page-badge">Page {{ $file }} of {{ $totalPages }}</span>
                </div>
                <div class="d-flex align-items-center gap-1 mt-2 mt-md-0">
                    <button class="btn btn-outline-primary btn-sm" onclick="prevPage()">
                        <i class="fas fa-chevron-left"></i> Prev
                    </button>
                    <input type="number" id="go-to-page" class="form-control form-control-sm" style="width:60px"
                        value="{{ $file }}" min="1" max="{{ $totalPages }}" onchange="goToPage()">
                    <button class="btn btn-outline-primary btn-sm" onclick="nextPage()">
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- PDF Canvas --}}
            <div class="pdf-container">
                <canvas id="pdf-canvas"></canvas>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" id="pdf-single-path"
                value="{{ $pdfPath ? route('pdfs.stream', basename($pdfPath)) : '' }}">
            <input type="hidden" id="pdf-base-path"
                value="{{ $pdfBasePath ? route('pdfs.stream', '') . $pdfBasePath : '' }}">
            <input type="hidden" id="current-page" value="{{ $file }}">
            <input type="hidden" id="total-pages" value="{{ $totalPages }}">
        </div>
    </div>
</div>
@endsection


<style>
body, html { background: #f8f9fa; }
.pdf-container { width: 100%; overflow: auto; text-align: center; padding-top:5px; }
#pdf-canvas {
    max-width: 100%;
    display: block;
    margin: auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 4px;
    transition: transform 0.2s;
}
.btn { border-radius: 4px !important; }
</style>


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfDoc = null;
let currentPage = parseInt($('#current-page').val());
let totalPages = parseInt($('#total-pages').val());
let scale = 1.5;
const canvas = document.getElementById('pdf-canvas');
const ctx = canvas.getContext('2d');

// Update page badge and input
function updatePageBadge() {
    $('#page-badge').text(`Page ${currentPage} of ${totalPages}`);
    $('#go-to-page').attr('max', totalPages);
}

// Render a PDF page
function renderPage(num){
    const pdfSinglePath = $('#pdf-single-path').val();
    const pdfBasePath = $('#pdf-base-path').val();

    let pdfPath = pdfSinglePath ? pdfSinglePath : pdfBasePath + num + '.pdf';

    pdfjsLib.getDocument(pdfPath).promise.then(pdf=>{
        pdfDoc = pdf;
        totalPages = pdf.numPages;

        // For split PDFs, each file has 1 page
        pdf.getPage(1).then(page=>{
            const viewport = page.getViewport({ scale: scale });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({ canvasContext: ctx, viewport: viewport });
        });

        currentPage = num;
        updatePageBadge();
        $('#go-to-page').val(num);
    });
}

// Navigation functions
function queuePage(num){ if(num>=1 && num<=totalPages) renderPage(num); }
function prevPage(){ if(currentPage>1) queuePage(currentPage-1); }
function nextPage(){ if(currentPage<totalPages) queuePage(currentPage+1); }
function goToPage(){ const p = parseInt($('#go-to-page').val()); queuePage(p); }

// Keyboard navigation
$(document).keydown(e=>{
    if(e.keyCode===37||e.keyCode===38){ e.preventDefault(); prevPage(); }
    else if(e.keyCode===39||e.keyCode===40){ e.preventDefault(); nextPage(); }
});

// Initialize PDF viewer
$(document).ready(function(){
    renderPage(currentPage);
});
</script>
@endpush
