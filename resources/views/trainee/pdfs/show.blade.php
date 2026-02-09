@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm border-0" style="height: calc(100vh - 120px);">
        
        {{-- PDF Controls Card Header --}}
        <div class="card-header bg-white border-bottom py-3" style="height: 70px; position: sticky; top: 0; z-index: 999;">
            <div class="d-flex justify-content-between align-items-center h-100">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">{{ $page_name }}</h5>
                    <span class="badge bg-primary px-3 py-2" id="page-badge">
                        Page 1 of {{ $totalPages }}
                    </span>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    {{-- Navigation Buttons --}}
                    <div class="btn-group">
                        <button class="btn btn-outline-primary px-3" onclick="prevPage()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="input-group" style="width: 120px;">
                            <input type="number" id="go-to-page" class="form-control text-center border-start-0 border-end-0" 
                                   value="1" min="1" max="{{ $totalPages }}" 
                                   onchange="goToPage()">
                            <span class="input-group-text bg-white">/{{ $totalPages }}</span>
                        </div>
                        <button class="btn btn-outline-primary px-3" onclick="nextPage()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    {{-- Download Button --}}
                    @if($pdfPath)
                    <a href="{{ route('pdfs.stream', basename($pdfPath)) }}" 
                       target="_blank" class="btn btn-success ms-2 px-3">
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                    
                    {{-- Zoom Controls --}}
                    <div class="btn-group ms-2">
                        <button class="btn btn-outline-secondary" onclick="zoomOut()" title="Zoom Out">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="zoomIn()" title="Zoom In">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- PDF View Area --}}
        <div class="card-body p-0 position-relative" style="height: calc(100% - 70px);">
            <div id="pdf-viewer-wrapper" class="h-100 w-100">
                <div id="pdf-container" class="h-100 w-100" style="overflow-y: auto; padding: 20px;">
                    {{-- PDF pages will load here --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Fields --}}
<input type="hidden" id="pdf-single-path" value="{{ $pdfPath ? route('pdfs.stream', basename($pdfPath)) : '' }}">
<input type="hidden" id="total-pages" value="{{ $totalPages }}">
@endsection

<style>
#pdf-container {
    scroll-behavior: smooth;
    background-color: #f8f9fa;
}
.pdf-page {
    display: block;
    margin: 15px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
    background: white;
    transition: all 0.3s ease;
}
.pdf-page:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}
.pdf-page.active {
    box-shadow: 0 0 0 3px #0d6efd, 0 6px 20px rgba(13,110,253,0.2);
    border: 2px solid #0d6efd;
}
#pdf-container::-webkit-scrollbar {
    width: 10px;
}
#pdf-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}
#pdf-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 5px;
}
#pdf-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
.card-header .btn-group .btn { border-radius: 0; }
.input-group .form-control { border-radius: 0; text-align: center; }
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// Global vars
let pdfDoc = null;
let scale = 1.3;
let totalPages = parseInt($('#total-pages').val());
let currentPage = 1;

// Initialize PDF
function initPDFViewer() {
    const pdfPath = $('#pdf-single-path').val();
    if(!pdfPath) return;

    pdfjsLib.getDocument(pdfPath).promise.then(pdf => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        renderPagesBatch(1, totalPages);
    }).catch(err => console.error(err));
}

// Render all pages
async function renderPagesBatch(start, end){
    const container = $('#pdf-container');
    container.empty();
    for(let i=start; i<=end; i++){
        const page = await pdfDoc.getPage(i);
        const viewport = page.getViewport({scale});
        const canvas = document.createElement('canvas');
        canvas.id = 'page-'+i;
        canvas.className = 'pdf-page';
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        const ctx = canvas.getContext('2d');
        await page.render({canvasContext: ctx, viewport: viewport}).promise;
        container.append(canvas);
    }
    setActivePage(1);
}

// Update page badge & input
function updatePageControls(){
    $('#page-badge').text(`Page ${currentPage} of ${totalPages}`);
    $('#go-to-page').val(currentPage);
}

// Set active page
function setActivePage(pageNum){
    $('.pdf-page').removeClass('active');
    $('#page-'+pageNum).addClass('active');
    currentPage = pageNum;
    updatePageControls();
}

// Scroll to page
function scrollToPage(pageNum){
    const page = $('#page-'+pageNum);
    const container = $('#pdf-container');
    if(page.length){
        const containerTop = container.offset().top;
        const pageTop = page.offset().top;
        const scroll = container.scrollTop() + (pageTop - containerTop);
        container.animate({scrollTop: scroll}, 300);
        setActivePage(pageNum);
    }
}

// Navigation
function prevPage(){ if(currentPage>1) scrollToPage(currentPage-1); }
function nextPage(){ if(currentPage<totalPages) scrollToPage(currentPage+1); }
function goToPage(){ 
    let p = parseInt($('#go-to-page').val());
    if(isNaN(p) || p<1) p=1;
    if(p>totalPages) p=totalPages;
    scrollToPage(p);
}

// Zoom
function zoomIn(){ scale+=0.2; refreshPDF(); }
function zoomOut(){ if(scale>0.5){ scale-=0.2; refreshPDF(); } }
async function refreshPDF(){ await renderPagesBatch(1,totalPages); scrollToPage(currentPage); }

// Scroll listener for active page highlight
$('#pdf-container').on('scroll', function(){
    const container = $(this);
    const scrollTop = container.scrollTop();
    const viewportHeight = container.height();
    let closest = 1;
    let minDistance = Infinity;
    $('.pdf-page').each(function(){
        const page = $(this);
        const offset = page.offset().top - container.offset().top;
        const distance = Math.abs(offset);
        if(distance < minDistance){ minDistance = distance; closest = parseInt(page.attr('id').split('-')[1]); }
    });
    if(closest !== currentPage) setActivePage(closest);
});

// Initialize
$(document).ready(function(){ initPDFViewer(); });
</script>
@endpush