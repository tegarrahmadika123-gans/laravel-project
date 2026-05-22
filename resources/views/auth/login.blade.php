@extends('layouts.guest')

@section('content')
<div class="login-box">
    <img src="{{ asset('image/logo_libraspace.png') }}" 
     alt="Logo LibraSpace" 
     class="logo-placeholder"
     style="width: 90px; margin-bottom: 20px;">
    <h2 class="fw-bold mb-1">Sign In</h2>
    <p class="text-muted mb-4">Akses Perpustakaan Digital</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Alamat Email</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control border-0 bg-light py-2" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Password</label>
            <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
                <span class="input-group-text border-0 bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control border-0 bg-light py-2" placeholder="••••••••" required>
            </div>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
    
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember">
        <label class="form-check-label small text-muted">Ingat saya</label>
    </div>

    <a href="{{ route('password.request') }}" 
   class="text-decoration-none small fw-bold text-dark">
    Lupa Password?
</a>

</div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3 border-0" style="background-color: #1e293b;">
            Masuk Sekarang
        </button>

        <div class="mt-4 text-center">
            <p class="small text-muted">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Akun</a>
            </p>
        </div>
    </form>
</div>
@endsection