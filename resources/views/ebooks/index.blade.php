@extends('layouts.app')

@section('content')
{{-- HERO SECTION --}}
<div class="hero-banner mb-4">

    <div class="hero-left">

        <h1>
            E-Book membuka <br>
            wawasan tanpa batas.
        </h1>

        <p>
            Baca dan download berbagai koleksi
            E-Book premium kapan saja.
        </p>

        <form action="/ebooks" method="GET">

            <div class="hero-search">

                <input
                    type="text"
                    name="search"
                    placeholder="Cari e-book, penulis, atau kategori..."
                    value="{{ request('search') }}"
                >

                <button type="submit">
                    Cari
                </button>

            </div>

        </form>

    </div>

    <div class="hero-right">

        <img
            src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1400&auto=format&fit=crop"
            alt="E-Book"
        >

    </div>

</div>
{{-- FEATURE --}}
<div class="hero-feature shadow-sm">

    <div class="feature-item">
        <i class="bi bi-journal-richtext"></i>

        <div>
            <strong>Koleksi Premium</strong>
            <span>Ribuan E-Book berkualitas</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-lightning-charge"></i>

        <div>
            <strong>Akses Cepat</strong>
            <span>Baca instan tanpa ribet</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-clock-history"></i>

        <div>
            <strong>24 Jam</strong>
            <span>Akses kapan saja</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-shield-check"></i>

        <div>
            <strong>Aman & Eksklusif</strong>
            <span>Khusus member aktif</span>
        </div>
    </div>

</div>

{{-- EBOOKS --}}
@php

$isMember = \App\Models\Membership::where('user_id', auth()->id())
    ->where('payment_status', 'paid')
    ->where('expired_at', '>', now())
    ->exists();

@endphp


<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 book-list">
    
    @foreach($ebooks as $ebook)
    <div class="col">
        <div class="card h-100 border-0 shadow-sm bg-transparent position-relative">

            @php
    $coverPath = $ebook->cover
        ? asset('storage/' . $ebook->cover)
        : 'https://via.placeholder.com/150x200?text=No+Cover';
@endphp

<img src="{{ $coverPath }}"
     class="card-img-top rounded-3 shadow-sm"
     style="aspect-ratio: 3/4; object-fit: cover;">

            <div class="card-body p-2 text-center">
                <p class="fw-bold mb-1 text-truncate small">{{ $ebook->judul }}</p>
                <p class="text-muted mb-2" style="font-size: 0.75rem;">
                    E-Book
                </p>

                @if(auth()->user()->role === 'admin')

    <div class="d-grid gap-2">

        {{-- EDIT --}}
        <button class="btn btn-warning rounded-pill py-1 fw-bold shadow-sm"
                style="font-size: 0.8rem;"
                data-bs-toggle="modal"
                data-bs-target="#editModal{{ $ebook->id }}">

            ✏️ Edit

        </button>

        {{-- HAPUS --}}
        <form action="/ebooks/delete/{{ $ebook->id }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="btn btn-outline-danger rounded-pill py-1 fw-bold shadow-sm w-100"
                    style="font-size: 0.8rem;">

                🗑️ Hapus

            </button>
        </form>

    </div>

@else

@if($isMember)

    <button class="btn btn-primary w-100 rounded-pill py-1 fw-bold shadow-sm"
            style="font-size: 0.8rem;"
            data-bs-toggle="modal"
            data-bs-target="#modalEbook{{ $ebook->id }}">

        📖 Baca Buku

    </button>

@else

    <button class="btn btn-danger w-100 rounded-pill py-1 fw-bold shadow-sm"
            style="font-size: 0.8rem;"
            data-bs-toggle="modal"
            data-bs-target="#memberModal">

        🔒 Khusus Member

    </button>

@endif

@endif
            </div>
        </div>
    </div>

    {{-- MODAL EBOOK --}}
    <div class="modal fade" id="modalEbook{{ $ebook->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">Akses E-Book</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="ebookForm{{ $ebook->id }}" method="POST">
    @csrf

                    <div class="modal-body p-4">
                        <div class="mb-3 text-center">
                            <h6 class="fw-bold">{{ $ebook->judul }}</h6>
                            <p class="text-muted small">Pastikan data di bawah sudah benar.</p>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Nomor Pokok Mahasiswa (NPM)</label>
                            <input type="number" name="npm" class="form-control rounded-3" required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">Alamat Email Kampus</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" required>
                        </div>

                        <div class="p-3 bg-light rounded-3 border-start border-warning border-4">
                            <p class="mb-1 fw-bold text-danger" style="font-size: 0.85rem;">Syarat & Ketentuan:</p>
                            <ul class="mb-0 small text-dark" style="font-size: 0.8rem;">
                                <li>Dilarang menyebarkan file di luar LibraSpace</li>
                                <li>Akan dikenakan sanksi jika melanggar</li>
                            </ul>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <div class="d-flex gap-2 w-100">

    {{-- BACA ONLINE --}}
    <button type="submit"
            formaction="/ebooks/read/{{ $ebook->id }}"
            class="btn btn-primary rounded-pill px-4 fw-bold w-50">

        📖 Baca Online

    </button>

    {{-- DOWNLOAD --}}
    <button type="submit"
            formaction="/ebooks/download/{{ $ebook->id }}"
            class="btn btn-success rounded-pill px-4 fw-bold w-50">

        📥 Download

    </button>

</div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@if(auth()->user()->role === 'admin')

<div class="modal fade" id="editModal{{ $ebook->id }}" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-warning rounded-top-4">

                <h5 class="modal-title fw-bold text-dark">
                    Edit E-Book
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <form action="/ebooks/update/{{ $ebook->id }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-body p-4">

                    {{-- JUDUL --}}
                    <div class="mb-3">

                        <label class="fw-bold small">
                            Judul
                        </label>

                        <input type="text"
                               name="judul"
                               class="form-control rounded-3"
                               value="{{ $ebook->judul }}"
                               required>

                    </div>

                    {{-- PENULIS --}}
                    <div class="mb-3">

                        <label class="fw-bold small">
                            Penulis
                        </label>

                        <input type="text"
                               name="penulis"
                               class="form-control rounded-3"
                               value="{{ $ebook->penulis }}"
                               required>

                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="mb-3">

                        <label class="fw-bold small">
                            Deskripsi
                        </label>

                        <textarea name="deskripsi"
                                  rows="4"
                                  class="form-control rounded-3">{{ $ebook->deskripsi }}</textarea>

                    </div>

                    {{-- COVER --}}
                    <div class="mb-3">

                        <label class="fw-bold small">
                            Cover Baru
                        </label>

                        <input type="file"
                               name="cover"
                               class="form-control rounded-3">

                    </div>

                    {{-- PDF --}}
                    <div class="mb-3">

                        <label class="fw-bold small">
                            PDF Baru
                        </label>

                        <input type="file"
                               name="pdf_file"
                               class="form-control rounded-3">

                    </div>

                </div>

                <div class="modal-footer border-0 pb-4">

                    <button class="btn btn-warning rounded-pill px-4 fw-bold w-100">

                        💾 Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif
    @endforeach
    
</div>

<footer class="fixed-bottom bg-primary text-white py-2 shadow-lg">
    <div class="container text-center">
        <small>© 2026 LibraSpace - All rights reserved | Email: libraspace@gmail.com</small>
    </div>
</footer>

<style>
body{
    background:#f7f1ea;
    padding-bottom:80px;
}

/* CARD */
.card{
    transition:.3s;
}

.card:hover{
    transform:translateY(-8px);
}

/* ================= HERO ================= */

.hero-banner{
    display:flex;
    align-items:center;
    min-height:280px; /* sebelumnya terlalu tinggi */
    margin-top:10px;
    overflow:hidden;
}

.hero-left{
    width:48%;
    padding:25px 45px;
}

.hero-left h1{
    font-size:52px;
    line-height:1.08;
    font-weight:800;
    color:#355070;

    margin-bottom:16px;

    font-family:Georgia, serif;
    letter-spacing:-1px;

    text-shadow:0 2px 10px rgba(53,80,112,.08);
}

.hero-left p{
    color:#5f6f81;
    font-size:16px;
    line-height:1.7;

    margin-bottom:22px;
    max-width:500px;
}

/* SEARCH */

.hero-search{
    display:flex;
    align-items:center;

    width:100%;
    max-width:470px;

    padding:6px;

    background:#fff;
    border-radius:16px;

    box-shadow:0 4px 15px rgba(0,0,0,.06);
}

.hero-search input{
    flex:1;
    border:none;
    outline:none;

    padding:12px 16px;

    background:transparent;
    font-size:14px;
}

.hero-search button{
    border:none;

    padding:11px 24px;

    background:#2563eb;
    color:#fff;

    border-radius:12px;
    font-weight:600;
}

/* HERO IMAGE */

.hero-right{
    width:52%;
    overflow:hidden;
    position:relative;
}

.hero-right img{
    width:100%;
    height:395px; /* dipendekin */
    object-fit:cover;

    border-top-right-radius:40px;

    -webkit-mask-image:linear-gradient(
        to left,
        rgba(0,0,0,1) 45%,
        rgba(0,0,0,0) 100%
    );

    mask-image:linear-gradient(
        to left,
        rgba(0,0,0,1) 45%,
        rgba(0,0,0,0) 100%
    );
}

/* ================= FEATURE ================= */

.hero-feature{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;

    margin-top:-18px; /* sebelumnya terlalu turun */
    padding:18px 24px;

    background:#fafafa;
    border:1px solid #f0f1f4;
    border-radius:0 0 24px 24px;
}

.feature-item{
    display:flex;
    align-items:flex-start;
    gap:12px;
}

.feature-item i{
    font-size:22px;
    color:#2563eb;
}

.feature-item strong{
    display:block;
    font-size:14px;
    color:#163b63;
}

.feature-item span{
    font-size:12px;
    color:#6b7c93;
}

/* ================= BOOK LIST ================= */

.book-list{
    width:92%;
    margin:40px auto 0; /* lebih naik */
}

.book-list .col{
    display:flex;
    justify-content:center;
}

.book-list .card{
    width:100%;
    max-width:185px;

    overflow:hidden;
    border-radius:18px;
}

.book-list .card img{
    width:100%;
    height:250px;

    object-fit:cover;
    border-radius:18px;

    aspect-ratio:3/4;
}

.book-list .card-body{
    padding-top:10px;
    padding-bottom:8px;
}

.book-list .card-body p.fw-bold{
    font-size:.92rem;
    margin-bottom:4px;
}

.book-list .btn{
    padding:6px 10px;
    font-size:.78rem;
}

/* ================= RESPONSIVE ================= */

@media(max-width:1200px){

    .book-list .card{
        max-width:170px;
    }

    .book-list .card img{
        height:230px;
    }

}

@media(max-width:991px){

    .hero-banner{
        flex-direction:column;
    }

    .hero-left,
    .hero-right{
        width:100%;
    }

    .hero-left{
        padding:30px;
    }

    .hero-left h1{
        font-size:40px;
    }

    .hero-right img{
        height:260px;
    }

    .hero-feature{
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:768px){

    .book-list{
        width:96%;
    }

    .book-list .card{
        max-width:150px;
    }

    .book-list .card img{
        height:210px;
    }

}

@media(max-width:576px){

    .hero-feature{
        grid-template-columns:1fr;
    }

    .hero-search{
        flex-direction:column;
        gap:8px;
    }

    .hero-search button{
        width:100%;
    }

}
</style>

@endsection