@extends('layouts.app')

@section('content')

<div class="ebook-reader-wrapper">

    {{-- HEADER --}}
    <div class="reader-header shadow-sm">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h3 class="reader-title mb-1">
                    📖 {{ $ebook->judul }}
                </h3>

                <small class="text-muted">
                    {{ $ebook->penulis }}
                </small>
            </div>

            {{-- CONTROL --}}
            <div class="reader-controls">

                <div class="reader-controls">

    <a href="/ebooks"
       class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">

        ← Kembali

    </a>

    <button id="prev" class="reader-btn">
        ⬅
    </button>

    <span class="page-info">

        Halaman
        <span id="page_num">1</span>
        /
        <span id="page_count">1</span>

    </span>

    <button id="next" class="reader-btn">
        ➡
    </button>

</div>

        </div>

    </div>

    {{-- PDF CONTAINER --}}
    <div class="pdf-container">

        <canvas id="pdf-render"></canvas>

    </div>

</div>

<style>

body{
    background:#f4f7fb;
}

/* WRAPPER */
.ebook-reader-wrapper{
    min-height:100vh;
    padding:30px;
}

/* HEADER */
.reader-header{

    background:white;

    border:none;

    padding:20px 25px;

    border-radius:20px;

    margin-bottom:25px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.05);
}

/* TITLE */
.reader-title{
    color:#0f172a;
    font-weight:700;
}

/* CONTROLS */
.reader-controls{
    display:flex;
    align-items:center;
    gap:15px;
}

.reader-btn{
    border:none;

    width:45px;
    height:45px;

    border-radius:14px;

    background:#2563eb;
    color:white;

    font-size:18px;
    font-weight:bold;

    transition:.2s;
}

.reader-btn:hover{
    transform:scale(1.08);
    background:#1d4ed8;
}

.page-info{
    color:#334155;
    font-weight:600;
}

/* PDF AREA */
.pdf-container{

    display:flex;
    justify-content:center;

    padding:40px;

    border-radius:24px;

    background:white;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.05);
}

/* CANVAS */
#pdf-render{

    border-radius:12px;

    box-shadow:
    0 15px 40px rgba(0,0,0,0.35);

    max-width:100%;
}

/* MOBILE */
@media(max-width:768px){

    .ebook-reader-wrapper{
        padding:15px;
    }

    .pdf-container{
        padding:15px;
    }

    .reader-controls{
        width:100%;
        justify-content:center;
    }

}

</style>

{{-- PDF JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>

const url =
"{{ asset('storage/' . $ebook->pdf_file) }}";

let pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 1.4,
    canvas = document.getElementById('pdf-render'),
    ctx = canvas.getContext('2d');

pdfjsLib.GlobalWorkerOptions.workerSrc =
'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

function renderPage(num){

    pageRendering = true;

    pdfDoc.getPage(num).then(function(page){

        const viewport =
        page.getViewport({ scale: scale });

        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        const renderTask =
        page.render(renderContext);

        renderTask.promise.then(function(){

            pageRendering = false;

            if(pageNumPending !== null){

                renderPage(pageNumPending);
                pageNumPending = null;

            }

        });

    });

    document.getElementById('page_num').textContent = num;
    updateButtons();
}

function updateButtons(){

    const prevBtn =
    document.getElementById('prev');

    const nextBtn =
    document.getElementById('next');

    // PREV
    if(pageNum <= 1){

        prevBtn.disabled = true;
        prevBtn.style.opacity = "0.4";
        prevBtn.style.cursor = "not-allowed";

    }else{

        prevBtn.disabled = false;
        prevBtn.style.opacity = "1";
        prevBtn.style.cursor = "pointer";
    }

    // NEXT
    if(pageNum >= pdfDoc.numPages){

        nextBtn.disabled = true;
        nextBtn.style.opacity = "0.4";
        nextBtn.style.cursor = "not-allowed";

    }else{

        nextBtn.disabled = false;
        nextBtn.style.opacity = "1";
        nextBtn.style.cursor = "pointer";
    }
}

function queueRenderPage(num){

    if(pageRendering){

        pageNumPending = num;

    }else{

        renderPage(num);

    }

}

function onPrevPage(){

    if(pageNum <= 1){
        return;
    }

    pageNum--;

    queueRenderPage(pageNum);
}

document.getElementById('prev')
.addEventListener('click', onPrevPage);

function onNextPage(){

    if(pageNum >= pdfDoc.numPages){
        return;
    }

    pageNum++;

    queueRenderPage(pageNum);
}

document.getElementById('next')
.addEventListener('click', onNextPage);

pdfjsLib.getDocument(url).promise.then(function(pdfDoc_){

    pdfDoc = pdfDoc_;

    document.getElementById('page_count')
    .textContent = pdfDoc.numPages;

    renderPage(pageNum);

});

</script>

@endsection