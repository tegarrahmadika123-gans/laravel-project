@extends('layouts.guest')

@section('content')
<div class="login-box">
    <img src="{{ asset('image/logo_libraspace.png') }}" 
     alt="Logo LibraSpace"
     class="logo-placeholder"
     style="width: 90px; margin-bottom: 20px;">
    <h2 class="fw-bold mb-1">Daftar Akun</h2>
    <p class="text-muted mb-4">Bergabung dengan komunitas pembaca kami</p>

    <form method="POST" action="{{ route('register') }}?registered=true">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Nama Lengkap</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control border-0 bg-light py-2" placeholder="Nama Anda" value="{{ old('name') }}" required>
            </div>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Alamat Email</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control border-0 bg-light py-2" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Password</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control border-0 bg-light py-2" placeholder="Minimal 8 karakter" required>
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label small fw-bold">Konfirmasi Password</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-check-circle"></i></span>
                <input type="password" name="password_confirmation" class="form-control border-0 bg-light py-2" placeholder="Ulangi password" required>
            </div>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3 border-0" style="background-color: #1e293b;">
            Daftar Sekarang
        </button>

        <div class="mt-4 text-center">
            <p class="small text-muted">Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
            </p>
        </div>
    </form>
</div>
@endsection