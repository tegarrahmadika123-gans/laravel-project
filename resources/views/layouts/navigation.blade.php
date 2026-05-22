<nav class="bg-white border-bottom shadow-sm fixed-top">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-md-2 d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center" href="/dashboard">
                    <img src="https://cdn-icons-png.flaticon.com/512/3429/3429149.png" alt="Logo" width="40" class="me-2">
                    <span class="fw-bold fs-5 text-dark">LibraSpace</span>
                </a>
            </div>

            <div class="col-md-7">
                <form action="/books" method="GET">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" name="search" class="form-control border-0 bg-light ps-4" placeholder="Cari judul buku, penulis atau kategori...">
                        <button class="btn btn-primary px-4 border-0" type="submit">
                            <i class="bi bi-search text-white"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-md-3 d-flex justify-content-end align-items-center">
                @auth
                    <div class="dropdown me-3">
                        <a class="text-decoration-none text-dark d-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="me-2 small fw-semibold text-muted">Halo,</span>
                            <span class="fw-bold">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="/profile">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit text-dark">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="/cart" class="btn btn-warning rounded-pill px-3 fw-bold d-flex align-items-center">
                         <span class="me-1">🛒</span> Paket
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="bg-white border-top border-bottom py-2">
        <div class="container">
            <ul class="nav justify-items-center">
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium px-3" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium px-3 border-start" href="/books">Daftar Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium px-3 border-start" href="/borrowings">Peminjaman</a>
                </li>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-primary fw-bold px-3 border-start" href="/books/create">
                                <span class="badge bg-danger">New</span> Tambah Buku
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    body {
    padding-top: 90px; /* sesuaikan tinggi navbar */
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">