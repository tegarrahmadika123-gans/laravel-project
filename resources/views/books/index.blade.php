@extends('layouts.app')

@section('content')

{{-- HERO SECTION --}}
<div class="hero-banner mb-4">

    <div class="hero-left">

        <h1>
            Buku adalah <br>
            jendela dunia.
        </h1>

        <p>
            Temukan buku terbaik dan perluas
            wawasan tanpa batas.
        </p>

        <form action="/books" method="GET">

            <div class="hero-search">

                <input
                    type="text"
                    name="search"
                    placeholder="Cari buku, penulis, atau kategori..."
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
            alt="Library"
        >

    </div>

</div>

{{-- FEATURE --}}
<div class="hero-feature shadow-sm">

    <div class="feature-item">
        <i class="bi bi-book"></i>

        <div>
            <strong>Koleksi Lengkap</strong>
            <span>Ribuan buku berkualitas</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-journal-check"></i>

        <div>
            <strong>Peminjaman Mudah</strong>
            <span>Proses cepat & praktis</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-clock-history"></i>

        <div>
            <strong>Akses Kapan Saja</strong>
            <span>Online 24/7</span>
        </div>
    </div>

    <div class="feature-item">
        <i class="bi bi-shield-check"></i>

        <div>
            <strong>Aman & Terpercaya</strong>
            <span>Data terlindungi</span>
        </div>
    </div>

</div>
</div>

    {{-- JARAK HERO & DAFTAR BUKU --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 book-list">
        @foreach($books as $book)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm bg-transparent position-relative">
                
                @if($book->stok <= 0)
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-3" 
                         style="background: rgba(0,0,0,0.6); z-index: 2; pointer-events: none;">
                        <span class="badge bg-danger fs-6 shadow-lg">STOK HABIS</span>
                    </div>
                @endif

                @php
                    // Logika untuk menampilkan gambar: Jika diawali http berarti URL, jika tidak berarti file lokal di folder storage
                    $coverPath = (Str::startsWith($book->cover_url, 'http')) 
                        ? $book->cover_url 
                        : asset('storage/' . $book->cover_url);
                @endphp
                <img src="{{ $book->cover_url ? $coverPath : 'https://via.placeholder.com/150x200?text=No+Cover' }}" 
                     class="card-img-top rounded-3 shadow-sm" 
                     alt="{{ $book->judul }}">
                
                <div class="card-body p-2 text-center">
                    <p class="fw-bold mb-0 text-truncate small" title="{{ $book->judul }}">{{ $book->judul }}</p>
                    <p class="text-muted mb-2" style="font-size: 0.75rem;">
                        Stok: <span class="badge bg-{{ $book->stok > 0 ? 'success' : 'danger' }}">{{ $book->stok }}</span>
                    </p>
                    
                    @if(auth()->user()->role === 'admin')
                        <div class="d-grid gap-1">
                            <button class="btn btn-warning btn-sm rounded-pill fw-bold shadow-sm" 
                                    data-bs-toggle="modal" data-bs-target="#modalEdit{{ $book->id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-pill fw-bold" 
                                    data-bs-toggle="modal" data-bs-target="#modalHapus{{ $book->id }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    @else
                        @if($book->stok > 0)
                            <button class="btn btn-primary w-100 rounded-pill py-1 fw-bold shadow-sm" 
                                    style="font-size: 0.8rem;" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalPinjam{{ $book->id }}">
                                Pinjam Buku
                            </button>
                        @else
                            <button class="btn btn-secondary w-100 rounded-pill py-1 fw-bold" 
                                    style="font-size: 0.8rem;" disabled>
                                Pinjam Buku
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="modal fade" id="modalEdit{{ $book->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-warning text-dark rounded-top-4">
                            <h5 class="modal-title fw-bold">Edit Data Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        {{-- PERUBAHAN 1: Menambahkan enctype="multipart/form-data" agar bisa kirim file --}}
                        <form action="/books/{{ $book->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="small fw-bold">Judul Buku</label>
                                    <input type="text" name="judul" class="form-control rounded-3" value="{{ $book->judul }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="small fw-bold d-block mb-2">Sampul Buku Saat Ini</label>
                                    {{-- Menampilkan preview sampul lama --}}
                                    <img src="{{ $book->cover_url ? $coverPath : 'https://via.placeholder.com/100x130?text=No+Cover' }}" 
                                         class="img-thumbnail rounded-3 mb-2" 
                                         style="height: 100px; width: 75px; object-fit: cover;">
                                    
                                    {{-- PERUBAHAN 2: Mengubah type="text" menjadi type="file" --}}
                                    <input type="file" name="cover_image" class="form-control rounded-3" accept="image/*">
                                    <small class="text-muted" style="font-size: 0.7rem;">Pilih file baru jika ingin mengganti sampul (Format: jpg, png, max 2MB)</small>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold">Jumlah Stok</label>
                                    <input type="number" name="stok" class="form-control rounded-3" value="{{ $book->stok }}" required min="0">
                                </div>
                            </div>
                            <div class="modal-footer border-0 pb-4 justify-content-center">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalHapus{{ $book->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-body p-4 text-center">
                            <i class="bi bi-exclamation-octagon text-danger fs-1 mb-3"></i>
                            <h5 class="fw-bold">Yakin hapus buku?</h5>
                            <p class="text-muted small">Data "{{ $book->judul }}" akan dihapus permanen.</p>
                            <form action="/books/{{ $book->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="d-flex gap-2 justify-content-center mt-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Yes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="modal fade" id="modalPinjam{{ $book->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white rounded-top-4">
                            <h5 class="modal-title fw-bold">Konfirmasi Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="/borrow/{{ $book->id }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="mb-3 text-center">
                                    <h6 class="fw-bold">{{ $book->judul }}</h6>
                                    <p class="text-muted small">Pastikan data di bawah sudah benar.</p>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold">Nomor Pokok Mahasiswa (NPM)</label>
                                    <input type="number" name="npm" class="form-control rounded-3" placeholder="Contoh: 202143501" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold">Alamat Email Kampus</label>
                                    <input type="email" name="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold">Durasi Pinjam (Hari)</label>
                                    <select name="tempo" class="form-select rounded-3" required>
                                        <option value="3">3 Hari</option>
                                        <option value="7">7 Hari</option>
                                        <option value="14">14 Hari</option>
                                    </select>
                                </div>
                                <div class="p-3 bg-light rounded-3 border-start border-warning border-4">
                                    <p class="mb-1 fw-bold text-danger" style="font-size: 0.85rem;">Syarat & Ketentuan Denda:</p>
                                    <ul class="mb-0 small text-dark" style="font-size: 0.8rem;">
                                        <li>Keterlambatan: <strong>Rp 2.000 / hari</strong>.</li>
                                        <li>Kehilangan/Kerusakan: <strong>Rp 100.000</strong>.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pb-4 justify-content-center">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Ya, Pinjam</button>
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
    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-8px); }

/* HERO */

.hero-banner{
    position:relative;
    display:flex;
    min-height:320px;

    /* HILANGKAN CARD */
    background:transparent;
    border-radius:0;
    box-shadow:none;
    overflow:hidden;

    margin-top:10px;
}

.hero-left{
    width:48%;
    padding:50px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

/* TEXT MENYATU DENGAN BG */
.hero-left h1{
    font-size:58px;
    line-height:1.08;
    font-weight:800;
    color:#355070;

    margin-bottom:20px;
    font-family:Georgia, serif;

    letter-spacing:-1px;
    text-shadow:
        0 2px 10px rgba(53,80,112,.08);
}

.hero-left p{
    color:#5f6f81;
    font-size:17px;
    line-height:1.8;
    margin-bottom:30px;
    max-width:520px;
}

/* SEARCH */

.hero-search{
    background:#fff;
    border-radius:16px;
    padding:6px;
    display:flex;
    width:100%;
    max-width:480px;
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
    background:#2563eb;
    color:#fff;
    border-radius:12px;
    padding:0 24px;
    font-weight:600;
}

/* KANAN */
.hero-right{
    width:52%;
    position:relative;
    overflow:hidden;

    border-top-right-radius:40px;
    border-bottom-right-radius:0px;
}

.hero-right img{
    width:100%;
    height:100%;
    object-fit:cover;

    /* sisi kanan cekung */
    border-top-right-radius:40px;
    border-bottom-right-radius:0px;

    /* efek gambar memudar ke kiri */
    -webkit-mask-image: linear-gradient(
        to left,
        rgba(0,0,0,1) 45%,
        rgba(0,0,0,0) 100%
    );

    mask-image: linear-gradient(
        to left,
        rgba(0,0,0,1) 45%,
        rgba(0,0,0,0) 100%
    );
}
/* FEATURE */

.hero-feature{
    background:#fafafa;
    margin-top:-18px;
    border-radius:0 0 24px 24px;
    padding:18px 28px;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    border:1px solid #f0f1f4;
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

/* TITLE DAFTAR BUKU */

.book-section-title{
    text-align:center;
    margin-top:55px;
    margin-bottom:10px;
}

.book-section-title h3{
    font-size:32px;
    font-weight:800;
    letter-spacing:2px;
    color:#163b63;
    margin-bottom:8px;
}

.book-section-title p{
    color:#6b7c93;
    font-size:14px;
}

/* RESPONSIVE */

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

    .hero-feature{
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:576px){

    .hero-feature{
        grid-template-columns:1fr;
    }

    
}

/* WRAPPER DAFTAR BUKU */
.book-list{
    width:92%;
    margin:60px auto 0 auto;
}

/* COLUMN */
.book-list .col{
    display:flex;
    justify-content:center;
}

/* CARD */
.book-list .card{
    width:100%;
    max-width:185px;
    border-radius:18px;
    overflow:hidden;
}

/* COVER BUKU */
.book-list .card img{
    height:250px;
    object-fit:cover;
    border-radius:18px;
}

/* BODY */
body{
    background:#f7f1ea;
    padding-bottom:80px;
}
.book-list .card-body{
    padding-top:10px;
    padding-bottom:8px;
}

/* JUDUL */
.book-list .card-body p.fw-bold{
    font-size:0.92rem;
    margin-bottom:4px;
}

/* BUTTON */
.book-list .btn{
    padding:6px 10px;
    font-size:0.78rem;
}

/* RESPONSIVE */
@media(max-width:1200px){

    .book-list .card{
        max-width:170px;
    }

    .book-list .card img{
        height:230px;
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
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Peminjaman Ditolak',
    text: "{{ session('error') }}",

    background: '#fff',
    color: '#1e293b',

    confirmButtonText: 'Mengerti',
    confirmButtonColor: '#dc3545',

    width: 400,

    showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
    },

    hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
    }
});
</script>
@endif


@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",

    background: '#fff',
    color: '#1e293b',

    timer: 2200,
    showConfirmButton: false,

    width: 400,

    showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
    },

    hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
    }
});
</script>
@endif

@endsection