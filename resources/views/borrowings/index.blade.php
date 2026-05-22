@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="borrowing-header mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">
                📚 Data Peminjaman
            </h2>

            <p class="text-muted mb-0">
                Monitor aktivitas peminjaman, pengembalian, dan denda perpustakaan.
            </p>
        </div>
    </div>
</div>

{{-- STATISTIK --}}
@php
    $totalDipinjam = $borrowings->where('status', 'dipinjam')->count();
    $totalPending = $borrowings->where('status', 'pending')->count();
    $totalKembali = $borrowings->where('status', 'kembali')->count();
    $totalDendaSemua = $borrowings->sum('total_denda');
@endphp

<div class="container">
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div>
                    <div class="stats-label">
                        Total Dipinjam
                    </div>

                    <div class="stats-value">
                        {{ $totalDipinjam }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning-subtle text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <div class="stats-label">
                        Pending
                    </div>

                    <div class="stats-value">
                        {{ $totalPending }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="stats-label">
                        Dikembalikan
                    </div>

                    <div class="stats-value">
                        {{ $totalKembali }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-danger-subtle text-danger">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <div>
                    <div class="stats-label">
                        Total Denda
                    </div>

                    <div class="stats-value">
                        Rp {{ number_format($totalDendaSemua,0,',','.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small">
                            <th class="ps-4 py-3">Informasi Buku</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="py-3">Peminjam</th>
                            @endif
                            <th class="py-3">Status & Waktu</th>
                            <th class="py-3 text-center">Denda</th>
                            <th class="py-3 text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $b)
                        @php
                            $tglKembali = \Carbon\Carbon::parse($b->tanggal_kembali);
                            $sekarang = now();
                            $sisaTeks = "";
                            $color = "text-muted";

                            if($b->status == 'dipinjam'){
                                if ($sekarang->gt($tglKembali)) {
                                    $diff = $tglKembali->diff($sekarang);
                                    $days = $diff->days;
                                    $hours = $diff->h;

                                    $sisaTeks = "Terlambat {$days} Hari {$hours} Jam";
                                    $color = "text-danger fw-bold";
                                } else {
                                    $diff = $sekarang->diff($tglKembali);
                                    $days = $diff->days;
                                    $hours = $diff->h;

                                    $sisaTeks = "Sisa {$days} Hari {$hours} Jam";
                                }
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="book-title">
                                    {{ $b->book->judul ?? '-' }}
                                </div>

                                <div class="book-date">Pinjam: {{ \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y') }}</div>
                            </td>

                            @if(auth()->user()->role === 'admin')
                            <td>
                                <div class="fw-semibold text-primary">{{ $b->user->name ?? 'User' }}</div>
                                <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $b->email }}</div>
                            </td>
                            @endif

                            <td>
                                <div class="mb-1">
                                    @if($b->status == 'pending')
                                        <span class="custom-badge badge-yellow">
                                            Pending
                                        </span>
                                    @elseif($b->status == 'dipinjam')
                                        <span class="custom-badge badge-blue">
                                            Dipinjam
                                        </span>
                                    @elseif($b->status == 'ditolak')
                                        <span class="custom-badge badge-red">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="custom-badge badge-green">
                                            Kembali
                                        </span>
                                    @endif
                                </div>

                                <small class="{{ $color }}">
                                    @if($b->status == 'dipinjam')
                                        <i class="bi bi-clock-history"></i>
                                        {{ $sisaTeks }}
                                    @elseif($b->status == 'pending')
                                        Menunggu Verifikasi
                                    @elseif($b->status == 'ditolak')
                                        <i class="bi bi-x-circle text-danger"></i>
                                        {{ $b->rejection_reason }}
                                    @else
                                        <i class="bi bi-check2-circle"></i>
                                        Selesai
                                    @endif
                                </small>
                            </td>

                            <td class="text-center">
                                @php
                                    $totalDenda = $b->total_denda;
                                @endphp

                                <div class="denda-box">
                                    <div class="fw-bold {{ $totalDenda > 0 ? 'text-danger' : 'text-muted' }}">
                                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                    </div>

                                    @if(($b->denda ?? 0) > 0)
                                        <div class="small text-muted">
                                            Denda Terlambat
                                        </div>
                                    @endif

                                    @if($b->denda_tambahan > 0)
                                        <div class="small text-warning">
                                            Denda Kerusakan
                                        </div>
                                    @endif

                                    @if($totalDenda > 0)
                                        <hr class="my-2">
                                        @if($b->payment_status == 'lunas')
                                            <div class="small text-success fw-bold">
                                                Denda Lunas
                                            </div>
                                        @else
                                            <div class="small text-danger fw-bold">
                                                Belum Dibayar
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <td class="pe-4 text-center">
                                @if(auth()->user()->role === 'admin')
                                <div class="d-flex gap-2 justify-content-center flex-wrap action-group">
                                    @if($b->status == 'pending')
                                        <form onsubmit="konfirmasiVerif(event)" action="/verify/{{ $b->id }}" method="POST" class="d-flex gap-1">
                                            @csrf
                                            <input type="text" name="kode" class="form-control form-control-sm" style="width: 70px" placeholder="Kode" required>
                                            <button class="btn btn-success btn-sm">
                                                Verif Pinjam
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{$b->id}}">
                                            Tolak
                                        </button>

                                        <form action="/borrow/resend/{{ $b->id }}" method="POST">
                                            @csrf
                                            <button class="btn btn-info btn-sm text-white">
                                                🔁 Resend
                                            </button>
                                        </form>

                                    @elseif($b->status == 'dipinjam')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{$b->id}}">
                                            <i class="bi bi-gear"></i> Manage
                                        </button>

                                        <form onsubmit="konfirmasiVerif(event)" action="/return/verify/{{ $b->id }}" method="POST" class="d-flex gap-1">
                                            @csrf
                                            <input type="text" name="kode" class="form-control form-control-sm" style="width: 70px" placeholder="Kode" required>
                                            <button class="btn btn-warning btn-sm shadow-sm">
                                                Verif Kembali
                                            </button>
                                        </form>

                                    @elseif($b->status == 'kembali' && $b->total_denda > 0 && $b->payment_status == 'belum_lunas')
                                        <form action="/borrow/pay/{{ $b->id }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">
                                                💰 Lunasi Denda
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @else
                                    {{-- AKSI USER --}}
                                    @if($b->status == 'pending')
                                        <span class="text-muted small">
                                            Menunggu Verifikasi Admin
                                        </span>
                                    @elseif($b->status == 'dipinjam')
                                        <form action="/return/request/{{ $b->id }}" method="POST">
                                            @csrf
                                            <button class="btn btn-warning btn-sm rounded-pill shadow-sm">
                                                🔄 Kembalikan Buku
                                            </button>
                                        </form>
                                    @elseif($b->status == 'kembali')
                                        <span class="badge bg-success">
                                            Buku Sudah Dikembalikan
                                        </span>
                                    @elseif($b->status == 'ditolak')
                                        <span class="badge bg-danger">
                                            Peminjaman Ditolak
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- KUMPULAN MODAL (DI LUAR STRUKTUR TABEL)    --}}
{{-- ========================================== --}}
@foreach($borrowings as $b)
    @if(auth()->user()->role === 'admin')
        
        {{-- Modal Manage Admin (Edit Tanggal & Denda Kerusakan) --}}
        @if($b->status == 'dipinjam')
        <div class="modal fade" id="editModal{{$b->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$b->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="editModalLabel{{$b->id}}">Manajemen Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/borrowing/update/{{ $b->id }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Ganti Tenggat Kembali (Simulasi)</label>
                                <input type="date" name="tanggal_kembali" class="form-control" value="{{ \Carbon\Carbon::parse($b->tanggal_kembali)->format('Y-m-d') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Denda Tambahan (Rusak/Hilang)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="denda_tambahan" class="form-control" value="{{ $b->denda_tambahan ?? 0 }}">
                                </div>
                            </div>

                            <div class="mb-1">
                                <label class="form-label text-muted small fw-bold">
                                    Status Pembayaran Denda
                                </label>
                                <select name="payment_status" class="form-select">
                                    <option value="belum_lunas" {{ $b->payment_status == 'belum_lunas' ? 'selected' : '' }}>
                                        Belum Lunas
                                    </option>
                                    <option value="lunas" {{ $b->payment_status == 'lunas' ? 'selected' : '' }}>
                                        Lunas
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary w-100 rounded-3">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- MODAL TOLAK PEMINJAMAN --}}
        @if($b->status == 'pending')
        <div class="modal fade" id="rejectModal{{$b->id}}" tabindex="-1" aria-labelledby="rejectModalLabel{{$b->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold text-danger" id="rejectModalLabel{{$b->id}}">
                            Tolak Peminjaman
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="/borrow/reject/{{ $b->id }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label class="form-label fw-semibold">
                                Alasan Penolakan
                            </label>
                            <textarea name="reason" class="form-control" rows="4" placeholder="Contoh: Buku sedang reservasi atau data tidak valid" required></textarea>
                        </div>
                        <div class="modal-footer border-0">
                            <button class="btn btn-danger w-100">
                                Tolak Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    @endif
@endforeach

<style>
body{

    background:#f5f7fb;

    color:#1e293b;

}



/* HEADER */

.borrowing-header{

    background:linear-gradient(135deg,#5993ea,#021c3e);

    padding:28px;

    border-radius:24px;

    box-shadow:0 8px 30px rgba(15,23,42,.06);

    border:1px solid #e8eef7;

}

/* TABLE */
.table{
    margin-bottom:0;
}

.table thead{
    background:#f8fafc;
}

.table thead th{
    font-size:13px;
    letter-spacing:.5px;
    color:#64748b;
    border:none;
    font-weight:700;
}

.table tbody tr{
    transition:.25s ease;
}

.table tbody tr:hover{
    background:#f8fbff;
}

.table tbody td{
    vertical-align:middle;
    border-color:#eef2f7;
    padding-top:20px;
    padding-bottom:20px;
}

/* JUDUL BUKU */
.book-title{
    font-weight:700;
    font-size:16px;
    color:#0f172a;
}

.book-date{
    color:#94a3b8;
    font-size:13px;
}

/* BADGE STATUS */
.custom-badge{
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
}

/* DIPINJAM */
.badge-blue{
    background:#dbeafe;
    color:#2563eb;
}

/* KEMBALI */
.badge-green{
    background:#dcfce7;
    color:#16a34a;
}

/* DITOLAK */
.badge-red{
    background:#fee2e2;
    color:#dc2626;
}

/* PENDING */
.badge-yellow{
    background:#fef3c7;
    color:#d97706;
}

/* BOX DENDA */
.denda-box{
    background:linear-gradient(135deg,#fff7ed,#fffbeb);
    border:1px solid #fde7c3;
    border-radius:16px;
    padding:14px 18px;
    min-width:180px;
    display:inline-block;
    text-align:center;
    line-height:1.6;
    box-shadow:0 4px 12px rgba(251,146,60,.08);
}

/* BUTTON */
.action-group .btn{
    border-radius:12px;
    font-size:13px;
    padding:8px 14px;
    border:none;
    transition:.2s ease;
}

/* HOVER BUTTON */
.action-group .btn:hover{
    transform:translateY(-2px);
}

/* CARD */
.card{
    overflow:hidden;
    border-radius:24px;
}

/* STATS CARD */
.stats-card{
    background:linear-gradient(145deg,#ffffff,#f9fbff);
    border:1px solid #edf2f7;
    border-radius:22px;
    padding:22px;
    display:flex;
    align-items:center;
    gap:18px;
    box-shadow:0 6px 24px rgba(15,23,42,.05);
    transition:.25s;
    height:100%;
}

.stats-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

/* ICON STATS */
.stats-icon{
    width:62px;
    height:62px;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

/* TEXT STATS */
.stats-label{
    font-size:14px;
    color:#64748b;
    margin-bottom:4px;
}

.stats-value{
    font-size:25px;
    font-weight:800;
    color:#0f172a;
}

/* ALERT */
.alert-success{
    background:#ecfdf3;
    color:#166534;
}

.alert-danger{
    background:#fef2f2;
    color:#991b1b;
}

/* MODAL */
.modal-content{
    border-radius:24px;
}

.modal-header{
    padding:20px 24px;
}

.modal-body{
    padding:24px;
}

.modal-footer{
    padding:20px 24px;
}

/* INPUT */
.form-control,
.form-select{
    border-radius:14px;
    border:1px solid #dbe4ee;
    padding:10px 14px;
    box-shadow:none !important;
}

.form-control:focus,
.form-select:focus{
    border-color:#3b82f6;
    box-shadow:0 0 0 4px rgba(59,130,246,.12) !important;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    // 1. Popup Kode Verifikasi dengan Animasi Lambat
    @if(session('return_code'))
        Swal.fire({
            title: 'KODE PENGEMBALIAN',
            html: '<h1 style="font-size: 4rem;" class="fw-bold text-primary animate__animated animate__pulse animate__infinite">{{ session("return_code") }}</h1><p class="text-muted">Tunjukkan kode ini kepada admin untuk memproses pengembalian buku.</p>',
            icon: 'info',
            showClass: { popup: 'animate__animated animate__fadeInDown animate__slow' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__slow' },
            confirmButtonText: 'Saya Sudah Simpan',
            allowOutsideClick: false,
            confirmButtonColor: '#0dcaf0'
        });
    @endif

    // 2. Notifikasi Sukses/Error Standar
    @if(session('success') && !session('return_code'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Peminjaman Ditolak',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc3545'
        });
    @endif

    // 3. Konfirmasi Admin sebelum Submit
    function konfirmasiVerif(event) {
        event.preventDefault();
        const form = event.target;

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
                        background:linear-gradient(135deg,#22c55e,#16a34a);
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:30px;
                        color:white;
                        box-shadow:0 10px 25px rgba(0,0,0,0.2);
                    ">
                        ✔️
                    </div>

                    <h4 style="font-weight:700; margin-bottom:5px;">
                        Proses Verifikasi
                    </h4>

                    <p style="color:#6c757d; font-size:14px;">
                        Pastikan buku sudah dicek dan kode valid
                    </p>

                    <div style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
                        <button id="btnConfirm" style="
                            border:none;
                            padding:10px 20px;
                            border-radius:10px;
                            background:#22c55e;
                            color:white;
                            font-weight:600;
                        ">
                            ✔️ Verifikasi
                        </button>

                        <button id="btnCancel" style="
                            border:none;
                            padding:10px 20px;
                            border-radius:10px;
                            background:#ef4444;
                            color:white;
                            font-weight:600;
                        ">
                            ❌ Batal
                        </button>
                    </div>
                </div>
            `,
            didOpen: () => {
                document.getElementById('btnConfirm').onclick = () => {
                    form.submit();
                };

                document.getElementById('btnCancel').onclick = () => {
                    Swal.close();
                };
            }
        });
    }
</script>
@endsection