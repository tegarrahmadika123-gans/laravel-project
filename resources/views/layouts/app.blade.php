@php
    use App\Models\Membership;

    $membership = null;

    if(auth()->check()){

        $membership = Membership::where('user_id', auth()->id())
    ->where('payment_status', 'paid')
    ->where('expired_at', '>', now())
            ->first();

    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraSpace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
/* GLOBAL */

body{
    font-family:'Inter', sans-serif;
    background:#f7f1ea;
    color:#22324a;
}
        /* NAVBAR */

.navbar{
    background:#ffffff;
    border-bottom:1px solid #ececec;
    backdrop-filter:blur(12px);
    position:sticky;
    top:0;
    z-index:999;
}

/* LOGO */

.brand-logo{
    width:60px;
    height:60px;
    border-radius:16px;

    display:flex;
    align-items:center;
    justify-content:center;

    overflow:hidden;

    box-shadow:
        0 10px 25px rgba(0,0,0,.08);
}

.brand-title{
    font-size:22px;
    color:#22324a;
    letter-spacing:-0.5px;
}

.brand-subtitle{
    font-size:11px;
    letter-spacing:1px;
}

/* MENU */

.custom-nav{
    color:#5c677d !important;
    font-weight:600;
    padding:10px 18px !important;
    border-radius:14px;
    transition:.25s;
}

.custom-nav:hover{
    background:#f3f6fb;
    color:#355070 !important;
}

.active-nav{
    background:#edf4ff;
    color:#2563eb !important;
}

/* USER */

.user-dropdown{
    display:flex;
    align-items:center;
    gap:12px;

    text-decoration:none;
    color:#22324a;
}

.user-avatar{
    width:42px;
    height:42px;
    border-radius:50%;

    background:linear-gradient(
        135deg,
        #355070,
        #6d597a
    );

    color:white;
    font-weight:700;

    display:flex;
    align-items:center;
    justify-content:center;
}

/* MEMBER BUTTON */

.member-btn{
    border:none;
    border-radius:999px;
    padding:11px 18px;

    font-weight:700;
    font-size:13px;

    transition:.25s;
}

.member-active{
    background:#ffe08a;
    color:#5c4300;
}

.member-inactive{
    background:#111827;
    color:white;
}

.member-btn:hover{
    transform:translateY(-2px);
}

/* MOBILE */

@media(max-width:991px){

    .navbar-collapse{
        margin-top:20px;
    }

    .navbar-nav{
        margin-top:10px;
        margin-bottom:15px;
    }

}

.modal-content{
    transform: scale(.92);
}

.payment-detail{
    display:none;
    background:#f8f9fa;
    border-radius:14px;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #e9ecef;
}

.payment-number{
    font-size:16px;
    font-weight:700;
    color:#0d6efd;
}

.payment-detail img{
    width:120px;
    border-radius:12px;
    border:1px solid #dee2e6;
}

.wa-btn{
    border-radius:14px;
    font-weight:600;
    padding:10px;
}

.user-dropdown::after{
    display:none;
}

.user-dropdown:focus{
    box-shadow:none;
}

.card{
    transition:.25s;
}

.card:hover{
    transform:translateY(-6px);
}
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">

    <div class="container">

        {{-- LOGO + BRAND --}}
        <a class="navbar-brand d-flex align-items-center gap-3" href="/">

            <div class="brand-logo">
            <img src="{{ asset('image/logo_libraspace.png') }}" 
             alt="LibraSpace Logo"
             style="width:100%; height:100%; object-fit:contain;">
            </div>

            <div class="d-flex flex-column lh-sm">
                <span class="fw-bold brand-title">
                    LibraSpace
                </span>

                <small class="text-muted brand-subtitle">
                    Digital Library Platform
                </small>
            </div>

        </a>

        {{-- TOGGLER MOBILE --}}
        <button class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse justify-content-between"
             id="navbarMenu">

            {{-- NAV MENU --}}
            <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-2">

                @auth

                    @if(auth()->user()->role === 'admin')

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('dashboard') ? 'active-nav' : '' }}"
                               href="/dashboard">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('books') ? 'active-nav' : '' }}"
                               href="/books">
                                Buku
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('ebooks') ? 'active-nav' : '' }}"
                               href="/ebooks">
                                E-Book
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('borrowings') ? 'active-nav' : '' }}"
                               href="/borrowings">
                                Peminjaman
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('books/create') ? 'active-nav' : '' }}"
                               href="/books/create">
                                Tambah Buku
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('admin/memberships') ? 'active-nav' : '' }}"
                               href="/admin/memberships">
                                Membership
                            </a>
                        </li>

                    @else

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('books') ? 'active-nav' : '' }}"
                               href="/books">
                                Buku
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('ebooks') ? 'active-nav' : '' }}"
                               href="/ebooks">
                                E-Book
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-nav {{ request()->is('borrowings') ? 'active-nav' : '' }}"
                               href="/borrowings">
                                Peminjaman
                            </a>
                        </li>

                    @endif

                @endauth

            </ul>

            {{-- USER --}}
            @auth

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">

                @php
                    $isMember = $membership ? true : false;
                @endphp

                @if(auth()->user()->role !== 'admin')

                    <button
                        class="member-btn
                        {{ $isMember ? 'member-active' : 'member-inactive' }}"

                        @if($isMember)
                            data-bs-toggle="tooltip"
                            title="Aktif sampai {{ $membership->expired_at->format('d M Y') }}"
                        @else
                            data-bs-toggle="modal"
                            data-bs-target="#memberModal"
                        @endif
                    >

                        @if($isMember)
                            👑 Gold Member
                        @else
                            ✨ Upgrade Member
                        @endif

                    </button>

                @endif

                {{-- DROPDOWN --}}
                <div class="dropdown">

                    <a class="user-dropdown dropdown-toggle"
                       href="#"
                       data-bs-toggle="dropdown">

                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </div>

                        <span class="fw-semibold">
                            {{ auth()->user()->name }}
                        </span>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">

                        <li>
                            <a class="dropdown-item rounded-3"
                               href="/profile">
                                <i class="bi bi-person me-2"></i>
                                Profil
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>

                            <form method="POST"
                                  action="{{ route('logout') }}">

                                @csrf

                                <button class="dropdown-item text-danger rounded-3"
                                        type="submit">

                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            </div>

            @endauth

        </div>

    </div>

</nav>

{{-- MODAL MEMBER --}}
<div class="modal fade" id="memberModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered" style="max-width:950px;">

        <div class="modal-content border-0 rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-dark text-white p-4 text-center">

                <h2 class="fw-bold mb-2">
                    👑 Membership LibraSpace
                </h2>

                <p class="mb-0 text-light">
                    Nikmati akses seluruh E-Book premium tanpa batas
                </p>

            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

<div class="row g-4">

    {{-- PAKET 1 --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body text-center p-3">

                <h5 class="fw-bold">
                    1 Minggu
                </h5>

                <h2 class="fw-bold text-primary my-2">
                    99K
                </h2>

                <ul class="list-unstyled small text-muted mb-3">
                    <li>✔ Semua E-Book</li>
                    <li>✔ Download PDF</li>
                    <li>✔ Baca Online</li>
                </ul>

                <form action="{{ route('membership.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden"
                           name="paket"
                           value="1 Minggu">

                    <input type="hidden"
                           name="harga"
                           value="99000">

                    <input type="hidden"
                           name="durasi_hari"
                           value="7">

                    <select name="payment_method"
                            class="form-select mb-3"
                            required>

                        <option value="">
                            Pilih Pembayaran
                        </option>

                        <option value="QRIS">QRIS</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="BCA">Bank BCA</option>

                    </select>
                    <div class="payment-detail">

    {{-- QRIS --}}
    <div class="qris-content d-none text-center">

        <img src="/image/qris.png" class="img-fluid mb-3">

        <div class="small text-muted">
            Scan QRIS di atas untuk melakukan pembayaran
        </div>

    </div>

    {{-- DANA / OVO --}}
    <div class="ewallet-content d-none text-center">

        <div class="text-muted small mb-1">
            Nomor Admin
        </div>

        <div class="payment-number">
            083807370504
        </div>

        <small class="text-muted">
            DANA / OVO
        </small>

    </div>

    {{-- BCA --}}
    <div class="bank-content d-none text-center">

        <div class="text-muted small mb-1">
            Rekening BCA
        </div>

        <div class="payment-number">
            1234567890
        </div>

        <small class="text-muted">
            A/N Admin LibraSpace
        </small>

    </div>

</div>

<button type="submit"
    class="btn btn-primary rounded-pill px-4 w-100 pilih-paket-btn">
    Pilih Paket
</button>

                </form>

            </div>

        </div>

    </div>

    {{-- PAKET 2 --}}
    <div class="col-md-4">

        <div class="card border-0 shadow rounded-4 h-100 border border-warning">

            <div class="card-body text-center p-3">

                <div class="badge bg-warning text-dark mb-2">
                    POPULER
                </div>

                <h5 class="fw-bold">
                    1 Bulan
                </h5>

                <h2 class="fw-bold text-warning my-3">
                    199K
                </h2>

                <ul class="list-unstyled small text-muted mb-3">
                    <li>✔ Semua E-Book</li>
                    <li>✔ Download PDF</li>
                    <li>✔ Baca Online</li>
                </ul>

                <form action="{{ route('membership.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden"
                           name="paket"
                           value="1 Bulan">

                    <input type="hidden"
                           name="harga"
                           value="199000">

                    <input type="hidden"
                           name="durasi_hari"
                           value="30">

                    <select name="payment_method"
                            class="form-select mb-3"
                            required>

                        <option value="">
                            Pilih Pembayaran
                        </option>

                        <option value="QRIS">QRIS</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="BCA">Bank BCA</option>

                    </select>

                    <div class="payment-detail">

    {{-- QRIS --}}
    <div class="qris-content d-none text-center">

        <img src="/image/qris.png" class="img-fluid mb-3">

        <div class="small text-muted">
            Scan QRIS di atas untuk melakukan pembayaran
        </div>

    </div>

    {{-- DANA / OVO --}}
    <div class="ewallet-content d-none text-center">

        <div class="text-muted small mb-1">
            Nomor Admin
        </div>

        <div class="payment-number">
            083807370504
        </div>

        <small class="text-muted">
            DANA / OVO
        </small>

    </div>

    {{-- BCA --}}
    <div class="bank-content d-none text-center">

        <div class="text-muted small mb-1">
            Rekening BCA
        </div>

        <div class="payment-number">
            1234567890
        </div>

        <small class="text-muted">
            A/N Admin LibraSpace
        </small>

    </div>

</div>

<button type="submit"
    class="btn btn-primary rounded-pill px-4 w-100 pilih-paket-btn">
    Pilih Paket
</button>

                </form>

            </div>

        </div>

    </div>

    {{-- PAKET 3 --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4 h-100">

            <div class="card-body text-center p-3">

                <h5 class="fw-bold">
                    1 Tahun
                </h5>

                <h2 class="fw-bold text-success my-3">
                    599K
                </h2>

                <ul class="list-unstyled small text-muted mb-3">
                    <li>✔ Semua E-Book</li>
                    <li>✔ Download PDF</li>
                    <li>✔ Baca Online</li>
                </ul>

                <form action="{{ route('membership.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden"
                           name="paket"
                           value="1 Tahun">

                    <input type="hidden"
                           name="harga"
                           value="599000">

                    <input type="hidden"
                           name="durasi_hari"
                           value="365">

                    <select name="payment_method"
                            class="form-select mb-3"
                            required>

                        <option value="">
                            Pilih Pembayaran
                        </option>

                        <option value="QRIS">QRIS</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="BCA">Bank BCA</option>

                    </select>
                    <div class="payment-detail">

    {{-- QRIS --}}
    <div class="qris-content d-none text-center">

        <img src="/image/qris.png" class="img-fluid mb-3">

        <div class="small text-muted">
            Scan QRIS di atas untuk melakukan pembayaran
        </div>

    </div>

    {{-- DANA / OVO --}}
    <div class="ewallet-content d-none text-center">

        <div class="text-muted small mb-1">
            Nomor Admin
        </div>

        <div class="payment-number">
            083807370504
        </div>

        <small class="text-muted">
            DANA / OVO
        </small>

    </div>

    {{-- BCA --}}
    <div class="bank-content d-none text-center">

        <div class="text-muted small mb-1">
            Rekening BCA
        </div>

        <div class="payment-number">
            1234567890
        </div>

        <small class="text-muted">
            A/N Admin LibraSpace
        </small>

    </div>

</div>

<button type="submit"
    class="btn btn-primary rounded-pill px-4 w-100 pilih-paket-btn">
    Pilih Paket
</button>

                </form>

            </div>

        </div>

    </div>

</div>

                {{-- INFO --}}
                <div class="alert alert-warning mt-4 rounded-4 border-0">

                    <div class="fw-bold mb-1">
                        Kenapa harus member?
                    </div>

                    <small>
                        Semua koleksi E-Book premium hanya dapat diakses oleh member aktif.
                        Semakin lama paket yang dipilih, semakin hemat biaya akses.
                    </small>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="container py-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        width: '320px',
        customClass: {
            popup: 'rounded-5 shadow-lg border-0',
            title: 'fw-bold small',
        },
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });

   @if(session('success'))
    Toast.fire({
        title: 'Hore! ✨',
        text: "{{ session('success') }}",
        icon: 'success',
    });

@elseif(session('error'))
    Toast.fire({
        title: 'Oops 😥',
        text: "{{ session('error') }}",
        icon: 'error',
    });

@elseif(session('status'))
    Toast.fire({
        title: 'Hore! ✨',
        text: "{{ session('status') == 'verification-link-sent' ? 'Link verifikasi dikirim!' : 'Hore kamu sudah login!' }}",
        icon: 'success',
    });
@endif
    
</script>

@if(session('return_code'))
<script>
Swal.fire({
    background: '#ffffff',
    backdrop: 'rgba(0,0,0,0.4)',
    showConfirmButton: false,
    showCloseButton: true,
    width: 420,
    padding: '2rem',
    html: `
        <div style="text-align:center">
            
            <div style="
                width:70px;
                height:70px;
                margin:0 auto 15px;
                border-radius:50%;
                background:linear-gradient(135deg,#4facfe,#00f2fe);
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:30px;
                color:white;
                box-shadow:0 10px 25px rgba(0,0,0,0.2);
            ">
                🔐
            </div>

            <h4 style="font-weight:700; margin-bottom:5px;">
                Kode Verifikasi
            </h4>

            <p style="color:#6c757d; font-size:14px;">
                Gunakan kode ini untuk proses verifikasi
            </p>

            <div id="kodeBox" style="
                margin-top:15px;
                font-size:32px;
                font-weight:bold;
                letter-spacing:5px;
                background:#f1f5f9;
                padding:12px;
                border-radius:12px;
                cursor:pointer;
                user-select:all;
            ">
                {{ session("return_code") }}
            </div>

            <button onclick="copyKode()" style="
                margin-top:15px;
                border:none;
                padding:10px 20px;
                border-radius:10px;
                background:#0d6efd;
                color:white;
                font-weight:600;
            ">
                📋 Copy Kode
            </button>

        </div>
    `,
    didOpen: () => {
        const el = document.getElementById('kodeBox');
        el.addEventListener('click', copyKode);
    }
});

function copyKode() {
    const text = "{{ session('return_code') }}";
    navigator.clipboard.writeText(text);

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Kode berhasil disalin!',
        showConfirmButton: false,
        timer: 1500
    });
}
</script>
@endif
<script>

document.querySelectorAll('select[name="payment_method"]').forEach(select => {

    select.addEventListener('change', function(){

        let form = this.closest('form');

        let detail = form.querySelector('.payment-detail');

        let qris = form.querySelector('.qris-content');

        let ewallet = form.querySelector('.ewallet-content');

        let bank = form.querySelector('.bank-content');

        detail.style.display = 'block';

        qris.classList.add('d-none');
        ewallet.classList.add('d-none');
        bank.classList.add('d-none');

        if(this.value === 'QRIS'){

            qris.classList.remove('d-none');

        }

        else if(this.value === 'DANA' || this.value === 'OVO'){

            ewallet.classList.remove('d-none');

        }

        else if(this.value === 'BCA'){

            bank.classList.remove('d-none');

        }

        let paket = form.querySelector('input[name="paket"]').value;
        let harga = form.querySelector('input[name="harga"]').value;


    });

});

</script>
<script>

document.querySelectorAll('.pilih-paket-btn').forEach(btn => {

    btn.addEventListener('click', function(e){

        e.preventDefault();

        let form = this.closest('form');

        let metode = form.querySelector('select[name="payment_method"]').value;

        if(metode === ''){

            Swal.fire({
                icon:'warning',
                title:'Pilih pembayaran dulu',
                text:'Silakan pilih metode pembayaran'
            });

            return;
        }

        let paket = form.querySelector('input[name="paket"]').value;
        let harga = form.querySelector('input[name="harga"]').value;

        let pesan =
`Halo Admin LibraSpace👋

Saya ingin membeli membership.

📦 Paket : ${paket}
💰 Harga : Rp ${harga}
💳 Pembayaran : ${metode}

Saya sudah melakukan pembayaran.
Mohon verifikasinya ya 🙏`;

        window.open(
            'https://wa.me/6283807370504?text='
            + encodeURIComponent(pesan),
            '_blank'
        );

        form.submit();

    });

});

</script>
<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

const tooltipList = [...tooltipTriggerList].map(
    tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)
);
</script>
</body>
</html>