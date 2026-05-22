@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="membership-title">
        👑 Membership Premium
    </h2>

    <div class="row g-4">

        {{-- 1 BULAN --}}
        <div class="col-md-6">

            <div class="membership-card h-100">

                <h3 class="fw-bold">
                    Gold Member
                </h3>

                <h1 class="fw-bold my-3">
                    Rp 49.000
                </h1>

                <p class="text-muted">
                    Akses semua e-book premium selama 30 hari.
                </p>

                <form action="{{ route('membership.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="hidden"
                           name="paket"
                           value="Gold Member">

                    <input type="hidden"
                           name="harga"
                           value="49000">

                    <input type="hidden"
                           name="durasi_hari"
                           value="30">

                    {{-- METODE --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Metode Pembayaran
                        </label>

                        <select name="payment_method"
                                class="form-select"
                                required>

                            <option value="">
                                Pilih Metode
                            </option>

                            <option value="QRIS">
                                QRIS
                            </option>

                            <option value="DANA">
                                DANA
                            </option>

                            <option value="OVO">
                                OVO
                            </option>

                            <option value="BCA">
                                Bank BCA
                            </option>

                        </select>

                    </div>

                    {{-- UPLOAD --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Upload Bukti Pembayaran
                        </label>

                        <input type="file"
                               name="payment_proof"
                               class="form-control"
                               required>

                    </div>

                    <button class="membership-btn">

                        Kirim Pembayaran

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<style>

body{
    background:#f5f7fb;
    color:#1e293b;
}

/* TITLE */
.membership-title{
    font-size:32px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:30px;
}

/* CARD */
.membership-card{
    background:linear-gradient(145deg,#ffffff,#f8fbff);
    border-radius:28px;
    padding:35px;
    border:1px solid #e8eef7;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
    transition:.3s ease;
    position:relative;
    overflow:hidden;
}

/* EFFECT HOVER */
.membership-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(15,23,42,.10);
}

/* GOLD EFFECT */
.membership-card::before{
    content:'';
    position:absolute;
    top:-40px;
    right:-40px;
    width:160px;
    height:160px;
    background:radial-gradient(circle,rgba(255,215,0,.18),transparent 70%);
}

/* PACKAGE TITLE */
.membership-card h3{
    font-size:28px;
    font-weight:800;
    color:#ca8a04;
    margin-bottom:10px;
}

/* PRICE */
.membership-card h1{
    font-size:48px;
    font-weight:900;
    color:#0f172a;
    margin-bottom:14px;
}

/* DESCRIPTION */
.membership-card p{
    color:#64748b;
    line-height:1.7;
    margin-bottom:25px;
}

/* LABEL */
.form-label{
    font-size:14px;
    font-weight:700;
    color:#334155;
    margin-bottom:8px;
}

/* INPUT */
.form-control,
.form-select{
    border-radius:16px;
    border:1px solid #dbe4ee;
    padding:12px 15px;
    box-shadow:none !important;
    transition:.2s;
}

/* FOCUS */
.form-control:focus,
.form-select:focus{
    border-color:#3b82f6;
    box-shadow:0 0 0 4px rgba(59,130,246,.12) !important;
}

/* BUTTON */
.membership-btn{
    background:linear-gradient(135deg,#0f172a,#1e293b);
    border:none;
    color:white;
    padding:14px;
    border-radius:16px;
    font-weight:700;
    transition:.25s ease;
    width:100%;
}

/* BUTTON HOVER */
.membership-btn:hover{
    transform:translateY(-2px);
    opacity:.95;
}

/* RESPONSIVE */
@media(max-width:768px){

    .membership-card{
        padding:28px;
    }

    .membership-card h1{
        font-size:40px;
    }

}

</style>

@endsection