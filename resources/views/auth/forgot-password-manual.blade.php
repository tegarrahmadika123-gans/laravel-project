@extends('layouts.guest')

@section('content')
<div class="login-box">

    <img src="{{ asset('image/logo_libraspace.png') }}" 
     alt="Logo LibraSpace"
     class="mb-3"
     style="width: 85px;">

    <h4 class="fw-bold mb-1">Reset Password</h4>
    <p class="text-muted mb-3 small">
        Masukkan data akun untuk mengganti password
    </p>

    {{-- ALERT --}}
    @if(session('error'))
        <div class="alert alert-danger py-2 small">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update.manual') }}">
        @csrf

        <!-- EMAIL -->
        <div class="mb-2 text-start">
            <label class="form-label small fw-bold">Email</label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email"
                    class="form-control border-0 bg-light py-2 small"
                    placeholder="nama@email.com" required>
            </div>
        </div>

        <!-- NAMA -->
        <div class="mb-2 text-start">
            <label class="form-label small fw-bold">Nama</label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" name="name"
                    class="form-control border-0 bg-light py-2 small"
                    placeholder="Nama lengkap" required>
            </div>
        </div>

        <!-- PASSWORD LAMA -->
        <div class="mb-2 text-start">
            <label class="form-label small fw-bold">
                Password Lama <span class="text-muted">(opsional)</span>
            </label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password_lama"
                    class="form-control border-0 bg-light py-2 small"
                    placeholder="••••••••">
            </div>
        </div>

        <!-- PASSWORD BARU -->
        <div class="mb-2 text-start">
            <label class="form-label small fw-bold">Password Baru</label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <input type="password" name="password"
                    class="form-control border-0 bg-light py-2 small"
                    placeholder="••••••••" required>
            </div>
        </div>

        <!-- KONFIRMASI -->
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Konfirmasi Password</label>
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-light border-0">
                    <i class="bi bi-check2-circle"></i>
                </span>
                <input type="password" name="password_confirmation"
                    class="form-control border-0 bg-light py-2 small"
                    placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit"
            class="btn w-100 py-2 fw-bold rounded-3 shadow-sm"
            style="background-color: #1e293b; color:white;">
            Reset Password
        </button>

        <div class="mt-3 text-center">
            <p class="small text-muted mb-0">
                Sudah ingat password?
                <a href="{{ route('login') }}"
                   class="fw-bold text-decoration-none">
                   Login
                </a>
            </p>
        </div>

    </form>
</div>
@endsection