<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Perpustakaan Kampus</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }

        .login-container {
            min-height: 100vh;
            overflow: hidden;
        }

        .form-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .visual-section {
    background-color: #0f172a;

    background-image:
        linear-gradient(
            rgba(15, 23, 42, 0.72),
            rgba(15, 23, 42, 0.82)
        ),
        url('{{ asset('image/libraspacebg.png') }}');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    border-top-left-radius: 60px;
    border-bottom-left-radius: 60px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    padding: 5rem;
    color: white;

    position: relative;
    overflow: hidden;
}

        /* input style */
        .input-group-text {
            border-radius: 10px 0 0 10px !important;
        }

        .form-control {
            border-radius: 0 10px 10px 0 !important;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(30, 41, 59, 0.1);
            border-color: #1e293b;
        }

        /* 🔥 SUPER SMOOTH FADE */
        .carousel-item {
            transition: opacity 1.2s ease-in-out !important;
        }

        /* indikator */
        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        /* optional: hover efek */
        .carousel-indicators button:hover {
            transform: scale(1.2);
        }

    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 login-container">

        <!-- FORM -->
        <div class="col-lg-5 form-section">
            <div style="width: 100%; max-width: 400px;">
                @yield('content')
            </div>
        </div>

        <!-- VISUAL -->
        <div class="col-lg-7 d-none d-lg-flex visual-section">

            <div id="textCarousel" class="carousel slide carousel-fade">

                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <h1 class="display-4 fw-bold mb-4">Digital Perpustakaan Kampus</h1>
                        <p class="lead">Kelola peminjaman buku, cek koleksi, dan akses literatur dalam satu platform.</p>
                    </div>

                    <div class="carousel-item">
                        <h1 class="display-4 fw-bold mb-4">Akses Ilmu Tanpa Batas</h1>
                        <p class="lead">Ribuan buku dan referensi akademik siap diakses kapan saja.</p>
                    </div>

                    <div class="carousel-item">
                        <h1 class="display-4 fw-bold mb-4">Belajar Lebih Cerdas</h1>
                        <p class="lead">Gunakan teknologi untuk memaksimalkan proses belajar kamu.</p>
                    </div>

                    <div class="carousel-item">
                        <h1 class="display-4 fw-bold mb-4">Satu Platform Semua Buku</h1>
                        <p class="lead">Cari, pinjam, dan kelola buku dengan sistem yang terintegrasi.</p>
                    </div>

                </div>

                <!-- garis -->
                <div class="mt-4">
                    <hr style="width: 60px; height: 5px; background: white; border: none; border-radius: 10px;">
                </div>

                <!-- indikator -->
                <div class="carousel-indicators position-relative mt-3 justify-content-start">
                    <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="3"></button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.querySelector('#textCarousel');

    if (el) {
        new bootstrap.Carousel(el, {
            interval: 3500,
            ride: 'carousel',
            pause: false,
            wrap: true,
            touch: true   // 🔥 swipe aktif
        });
    }
});
</script>

<script>
const Toast = Swal.mixin({
    width: '320px',
    customClass: {
        popup: 'rounded-5 shadow-lg border-0',
        title: 'fw-bold small',
        htmlContainer: 'small'
    },
    confirmButtonColor: '#1e293b'
});

@if(session('success'))
Toast.fire({
    title: 'Hore! Berhasil ✨',
    text: "{{ session('success') }}",
    icon: 'success',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if(session('status'))
Toast.fire({
    title: 'Login Berhasil',
    icon: 'success',
    timer: 2000,
    showConfirmButton: false
});
@endif

@if($errors->any())
Toast.fire({
    title: 'Login Gagal',
    text: 'Cek email/password',
    icon: 'error'
});
@endif
</script>

</body>
</html>