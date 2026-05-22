@extends('layouts.app')

@section('content')

<div class="membership-wrap">

    {{-- HEADER --}}
    <div class="membership-header">

        <div>
            <h2>👑 Membership libraspace</h2>
            <p>Kelola approval membership pengguna LibraSpace</p>
        </div>

        <div class="header-stat">
            {{ $memberships->count() }} Total
        </div>

    </div>

    {{-- TABLE CARD --}}
    <div class="membership-card">

        <div class="table-responsive">

            <table class="table custom-table align-middle mb-0">

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Paket</th>
                        <th>Harga</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($memberships as $item)

                    <tr>

                        {{-- USER --}}
                        <td>
                            <div class="user-info">

                                <div class="user-avatar">
                                    {{ strtoupper(substr($item->user->name,0,1)) }}
                                </div>

                                <div>
                                    <strong>
                                        {{ $item->user->name }}
                                    </strong>

                                    <small>
                                        {{ $item->user->email }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        {{-- PAKET --}}
                        <td>

                            <span class="package-badge">
                                {{ $item->paket }}
                            </span>

                        </td>

                        {{-- HARGA --}}
                        <td>

                            <strong class="price-text">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </strong>

                        </td>

                        {{-- METODE --}}
                        <td>

                            <span class="method-pill">
                                {{ $item->payment_method }}
                            </span>

                        </td>

                        {{-- STATUS --}}
                        <td>

                            @if($item->payment_status == 'paid')

                                <span class="status-badge success">
                                    ● Paid
                                </span>

                            @elseif($item->payment_status == 'rejected')

                                <span class="status-badge danger">
                                    ● Rejected
                                </span>

                            @else

                                <span class="status-badge warning">
                                    ● Pending
                                </span>

                            @endif

                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">

                            @if($item->payment_status == 'pending')

                            <div class="action-group">

                                <form method="POST"
                                      action="/admin/memberships/{{ $item->id }}/approve">

                                    @csrf

                                    <button class="btn approve-btn">

                                        ✓ Approve

                                    </button>

                                </form>

                                <form method="POST"
                                      action="/admin/memberships/{{ $item->id }}/reject">

                                    @csrf

                                    <button class="btn reject-btn">

                                        ✕ Reject

                                    </button>

                                </form>

                            </div>

                            @else

                                <span class="done-text">
                                    Tidak ada aksi
                                </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    📭
                                </div>

                                <h5>
                                    Belum ada membership
                                </h5>

                                <p>
                                    Data membership user akan muncul di sini.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection
<style>

/* BODY */
/* =========================
   GLOBAL
========================= */

body{
    background:
        radial-gradient(circle at top left,#eef4ff,#f8fbff 40%,#f5f7fb 100%);
    font-family:'Inter',sans-serif;
    color:#0f172a;
}

/* WRAPPER */
.membership-wrap{
    padding:24px;
    max-width:1450px;
    margin:auto;
}

/* =========================
   HEADER
========================= */

.membership-header{
    position:relative;
    overflow:hidden;

    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:24px 28px;
    margin-bottom:24px;

    border-radius:26px;

    background:
        linear-gradient(135deg,#1e293b,#334155);

    box-shadow:
        0 12px 40px rgba(15,23,42,.12);

    transition:.3s ease;
}

.membership-header::before{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    background:rgba(255,255,255,.06);
    border-radius:50%;
    top:-80px;
    right:-50px;
}

.membership-header:hover{
    transform:translateY(-3px);
}

.membership-header h2{
    color:white;
    font-size:1.7rem;
    font-weight:800;
    margin-bottom:4px;
}

.membership-header p{
    color:rgba(255,255,255,.75);
    margin:0;
    font-size:.92rem;
}

/* TOTAL */
.header-stat{
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(14px);

    border:1px solid rgba(255,255,255,.12);

    color:white;

    padding:12px 18px;

    border-radius:16px;

    font-size:.88rem;
    font-weight:700;

    transition:.25s ease;
}

.header-stat:hover{
    transform:scale(1.05);
    background:rgba(255,255,255,.18);
}

/* =========================
   TABLE CARD
========================= */

.membership-card{
    background:rgba(255,255,255,.72);

    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.5);

    border-radius:28px;

    overflow:hidden;

    box-shadow:
        0 10px 35px rgba(15,23,42,.06);

    transition:.3s ease;
}

.membership-card:hover{
    transform:translateY(-4px);
    box-shadow:
        0 18px 45px rgba(15,23,42,.09);
}

/* =========================
   TABLE
========================= */

.custom-table{
    margin:0;
}

.custom-table thead{
    background:
        linear-gradient(to right,#f8fbff,#f1f5f9);
}

.custom-table thead th{
    border:none;
    padding:22px 18px;

    font-size:.74rem;
    font-weight:800;

    letter-spacing:.7px;
    text-transform:uppercase;

    color:#64748b;
}

/* ROW */
.custom-table tbody tr{
    transition:.25s ease;
    border-bottom:1px solid #edf2f7;
}

.custom-table tbody tr:hover{
    background:
        linear-gradient(to right,#f8fbff,#f1f7ff);

    transform:scale(1.002);
}

/* TD */
.custom-table td{
    padding:22px 18px;
    border:none;
    vertical-align:middle;
}

/* =========================
   USER
========================= */

.user-info{
    display:flex;
    align-items:center;
    gap:14px;
}

/* AVATAR */
.user-avatar{
    width:54px;
    height:54px;

    border-radius:18px;

    background:
        linear-gradient(135deg,#4f46e5,#6366f1);

    color:white;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:1rem;
    font-weight:800;

    box-shadow:
        0 10px 24px rgba(79,70,229,.25);

    transition:.3s ease;
}

.user-avatar:hover{
    transform:rotate(-6deg) scale(1.08);
}

/* USER TEXT */
.user-info strong{
    display:block;
    font-size:.94rem;
    margin-bottom:2px;
    color:#0f172a;
}

.user-info small{
    color:#64748b;
    font-size:.76rem;
}

/* =========================
   PACKAGE
========================= */

.package-badge{
    background:
        linear-gradient(135deg,#eef2ff,#e0e7ff);

    color:#4338ca;

    padding:10px 16px;

    border-radius:999px;

    font-size:.75rem;
    font-weight:700;

    display:inline-flex;
    align-items:center;
    gap:6px;

    transition:.25s ease;
}

.package-badge:hover{
    transform:translateY(-2px);
}

/* =========================
   PRICE
========================= */

.price-text{
    font-size:.95rem;
    font-weight:800;
    color:#0f172a;
}

/* =========================
   PAYMENT
========================= */

.method-pill{
    background:#f8fafc;

    border:1px solid #e2e8f0;

    padding:9px 15px;

    border-radius:999px;

    font-size:.75rem;
    font-weight:700;

    color:#334155;

    transition:.25s ease;
}

.method-pill:hover{
    background:#eef4ff;
}

/* =========================
   STATUS
========================= */

.status-badge{
    padding:9px 15px;

    border-radius:999px;

    font-size:.75rem;
    font-weight:700;

    display:inline-flex;
    align-items:center;
    gap:6px;

    transition:.25s ease;
}

.status-badge:hover{
    transform:scale(1.04);
}

/* SUCCESS */
.status-badge.success{
    background:#dcfce7;
    color:#166534;
}

/* WARNING */
.status-badge.warning{
    background:#fef3c7;
    color:#92400e;
}

/* DANGER */
.status-badge.danger{
    background:#fee2e2;
    color:#991b1b;
}

/* =========================
   ACTIONS
========================= */

.action-group{
    display:flex;
    gap:10px;
    justify-content:center;
}

/* BUTTON */
.approve-btn,
.reject-btn{
    border:none;

    border-radius:14px;

    padding:10px 18px;

    font-size:.76rem;
    font-weight:700;

    transition:.25s ease;
}

/* APPROVE */
.approve-btn{
    background:
        linear-gradient(135deg,#10b981,#059669);

    color:white;

    box-shadow:
        0 8px 18px rgba(16,185,129,.18);
}

.approve-btn:hover{
    transform:translateY(-3px) scale(1.03);

    box-shadow:
        0 14px 25px rgba(16,185,129,.25);

    color:white;
}

/* REJECT */
.reject-btn{
    background:
        linear-gradient(135deg,#ef4444,#dc2626);

    color:white;

    box-shadow:
        0 8px 18px rgba(239,68,68,.18);
}

.reject-btn:hover{
    transform:translateY(-3px) scale(1.03);

    box-shadow:
        0 14px 25px rgba(239,68,68,.24);

    color:white;
}

/* DONE */
.done-text{
    color:#94a3b8;
    font-size:.8rem;
    font-weight:600;
}

/* =========================
   EMPTY
========================= */

.empty-state{
    text-align:center;
    padding:70px 20px;
}

.empty-icon{
    font-size:4rem;
    margin-bottom:12px;
}

.empty-state h5{
    font-weight:800;
    margin-bottom:6px;
}

.empty-state p{
    color:#64748b;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:992px){

    .membership-header{
        flex-direction:column;
        align-items:flex-start;
        gap:18px;
    }

}

@media(max-width:768px){

    .membership-wrap{
        padding:14px;
    }

    .custom-table{
        min-width:920px;
    }

    .action-group{
        flex-direction:column;
    }

}

</style>

