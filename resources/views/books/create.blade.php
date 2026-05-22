@extends('layouts.app')

@section('content')

<div class="book-page">

    {{-- HERO --}}
    <div class="book-hero">

        <div class="hero-content">

            <div class="hero-badge">
                📚 Library Space
            </div>

            <h1>
                Kelola Buku & E-Book
                <span>LibraSpaceS</span>
            </h1>

            <p>
                Tambahkan koleksi fisik maupun digital dengan tampilan modern,
                lebih clean, premium, dan nyaman digunakan admin.
            </p>

        </div>

        <div class="hero-circle one"></div>
        <div class="hero-circle two"></div>

    </div>

    {{-- CONTENT --}}
    <div class="row g-4 mt-1">

        {{-- BUKU FISIK --}}
        <div class="col-xl-6">

            <div class="modern-card dark-card">

                <div class="card-top">

                    <div class="card-icon">
                        📖
                    </div>

                    <div>
                        <h3>Tambah Buku Fisik</h3>
                        <p>Kelola stok buku perpustakaan</p>
                    </div>

                </div>

                <form action="/books/store"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="form-grid">

                        <div class="form-group full">
                            <label>Judul Buku</label>

                            <input type="text"
                                   name="judul"
                                   placeholder="Masukkan judul buku..."
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Penulis</label>

                            <input type="text"
                                   name="penulis"
                                   placeholder="Nama penulis"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>

                            <input type="text"
                                   name="kategori"
                                   placeholder="Kategori / Genre"
                                   required>
                        </div>

                        <div class="form-group full">
                            <label>Jumlah Stok</label>

                            <input type="number"
                                   name="stok"
                                   placeholder="Jumlah stok tersedia"
                                   required>
                        </div>

                        <div class="form-group full">

                            <label>Upload Cover Buku</label>

                            <div class="upload-box">

                                <div class="upload-icon">
                                    ⬆️
                                </div>

                                <h5>
                                    Upload Cover Buku
                                </h5>

                                <p>
                                    PNG, JPG maksimal 2MB
                                </p>

                                <input type="file"
                                       name="cover_image"
                                       accept="image/*"
                                       required>

                            </div>

                        </div>

                    </div>

                    <button class="submit-btn dark-btn">
                        Simpan Buku
                    </button>

                </form>

            </div>

        </div>

        {{-- EBOOK --}}
        <div class="col-xl-6">

            <div class="modern-card blue-card">

                <div class="card-top">

                    <div class="card-icon blue">
                        📘
                    </div>

                    <div>
                        <h3>Upload E-Book</h3>
                        <p>Publikasikan PDF digital premium</p>
                    </div>

                </div>

                <form action="/ebooks/store"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Judul E-Book</label>

                            <input type="text"
                                   name="judul"
                                   placeholder="Judul e-book"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Penulis</label>

                            <input type="text"
                                   name="penulis"
                                   placeholder="Nama penulis"
                                   required>
                        </div>

                        <div class="form-group full">
                            <label>Deskripsi</label>

                            <textarea name="deskripsi"
                                      rows="4"
                                      placeholder="Deskripsi atau sinopsis ebook..."></textarea>
                        </div>

                        <div class="form-group">

                            <label>Cover E-Book</label>

                            <input type="file"
                                   name="cover"
                                   accept="image/*"
                                   required>

                        </div>

                        <div class="form-group">

                            <label>File PDF</label>

                            <input type="file"
                                   name="pdf_file"
                                   accept=".pdf"
                                   required>

                        </div>

                    </div>

                    <button class="submit-btn blue-btn">
                        Publikasikan E-Book
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


<style>

/* =========================
   GLOBAL
========================= */

body{
    background:
        linear-gradient(to bottom,#f8fbff,#eef4ff);
    font-family:'Inter',sans-serif;
    color:#0f172a;
}

.book-page{
    max-width:1400px;
    margin:auto;
    padding:18px;
}

/* =========================
   HERO
========================= */

.book-hero{
    position:relative;
    overflow:hidden;
    border-radius:22px;
    padding:28px 34px;
    background:
        linear-gradient(135deg,#0f172a,#1e293b,#2563eb);
    margin-bottom:20px;
    color:white;
    box-shadow:
        0 10px 24px rgba(37,99,235,.12);
}

.hero-content{
    position:relative;
    z-index:2;
    max-width:520px;
}

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 12px;
    border-radius:999px;
    background:rgba(255,255,255,.12);
    font-size:.68rem;
    font-weight:700;
    margin-bottom:12px;
}

.book-hero h1{
    font-size:1.8rem;
    line-height:1.15;
    font-weight:800;
    margin-bottom:10px;
}

.book-hero h1 span{
    color:#93c5fd;
}

.book-hero p{
    font-size:.82rem;
    line-height:1.6;
    color:rgba(255,255,255,.8);
    max-width:470px;
    margin-bottom:0;
}

/* HERO SHAPE */

.hero-circle{
    position:absolute;
    border-radius:50%;
    filter:blur(18px);
}

.hero-circle.one{
    width:120px;
    height:120px;
    background:rgba(59,130,246,.18);
    top:-35px;
    right:-20px;
}

.hero-circle.two{
    width:80px;
    height:80px;
    background:rgba(255,255,255,.08);
    bottom:-20px;
    right:90px;
}

/* MOBILE */

@media(max-width:768px){

    .book-hero{
        padding:22px 20px;
        border-radius:18px;
    }

    .book-hero h1{
        font-size:1.45rem;
    }

    .book-hero p{
        font-size:.78rem;
    }

}

/* =========================
   CARD
========================= */

.modern-card{
    background:white;
    border-radius:24px;
    padding:22px;
    box-shadow:
        0 8px 24px rgba(15,23,42,.05);
    height:100%;
    position:relative;
    overflow:hidden;
    transition:.28s ease;
}

.modern-card:hover{
    transform:translateY(-3px);
    box-shadow:
        0 14px 30px rgba(15,23,42,.08);
}

/* TOP */

.card-top{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:22px;
}

.card-icon{
    width:54px;
    height:54px;
    border-radius:18px;
    background:
        linear-gradient(135deg,#111827,#374151);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.3rem;
    color:white;
    flex-shrink:0;
    box-shadow:
        0 8px 18px rgba(17,24,39,.18);
}

.card-icon.blue{
    background:
        linear-gradient(135deg,#2563eb,#60a5fa);
    box-shadow:
        0 8px 18px rgba(37,99,235,.2);
}

.card-top h3{
    font-size:1.08rem;
    font-weight:800;
    margin-bottom:3px;
}

.card-top p{
    color:#64748b;
    margin:0;
    font-size:.84rem;
}

/* =========================
   FORM
========================= */

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.form-group.full{
    grid-column:1/-1;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:.72rem;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.5px;
    color:#64748b;
}

/* INPUT */

.form-group input,
.form-group textarea{
    width:100%;
    border:none;
    background:#f8fafc;
    border-radius:14px;
    padding:13px 15px;
    font-size:.88rem;
    transition:.25s ease;
    color:#0f172a;
    border:1px solid transparent;
}

.form-group input:focus,
.form-group textarea:focus{
    outline:none;
    background:white;
    border-color:#3b82f6;
    box-shadow:
        0 0 0 4px rgba(59,130,246,.08);
}

textarea{
    resize:none;
}

/* =========================
   UPLOAD BOX
========================= */

.upload-box{
    position:relative;
    border:2px dashed #dbeafe;
    background:
        linear-gradient(to bottom,#f8fbff,#f1f5ff);
    border-radius:20px;
    padding:24px 18px;
    text-align:center;
    transition:.25s;
}

.upload-box:hover{
    border-color:#3b82f6;
}

.upload-icon{
    width:56px;
    height:56px;
    border-radius:18px;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:1.4rem;
    margin-bottom:12px;
}

.upload-box h5{
    font-weight:800;
    font-size:1rem;
    margin-bottom:6px;
}

.upload-box p{
    color:#64748b;
    font-size:.82rem;
    margin-bottom:14px;
}

/* FILE */

input[type=file]{
    background:white !important;
    border-radius:12px !important;
    padding:10px !important;
}

/* =========================
   BUTTON
========================= */

.submit-btn{
    width:100%;
    border:none;
    border-radius:16px;
    padding:15px;
    margin-top:22px;
    color:white;
    font-weight:800;
    font-size:.88rem;
    letter-spacing:.3px;
    transition:.25s ease;
}

.dark-btn{
    background:
        linear-gradient(135deg,#111827,#1e293b);
}

.blue-btn{
    background:
        linear-gradient(135deg,#2563eb,#3b82f6);
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:
        0 10px 22px rgba(37,99,235,.16);
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:992px){

    .form-grid{
        grid-template-columns:1fr;
    }

    .book-hero{
        padding:32px;
    }

    .book-hero h1{
        font-size:2rem;
    }

}

@media(max-width:768px){

    .book-page{
        padding:12px;
    }

    .book-hero{
        border-radius:22px;
        padding:28px 22px;
    }

    .book-hero h1{
        font-size:1.7rem;
    }

    .modern-card{
        border-radius:20px;
        padding:18px;
    }

}

</style>