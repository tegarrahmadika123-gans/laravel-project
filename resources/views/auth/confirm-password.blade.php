@extends('layouts.guest')

@section('content')

<div class="text-center mb-4">
    <h3 class="fw-bold">Konfirmasi Password 🔒</h3>
    <p class="text-muted small">
        Masukkan password untuk melanjutkan
    </p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-3 text-start">
        <label class="form-label fw-bold small">Password</label>

        <div class="input-group border rounded-3 overflow-hidden bg-light shadow-sm">
            <span class="input-group-text border-0 bg-light">
                <i class="bi bi-lock"></i>
            </span>

            <input 
                type="password" 
                name="password" 
                class="form-control border-0 bg-light"
                placeholder="Masukkan password"
                required
            >
        </div>

        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit"
        class="btn w-100 py-2 fw-bold shadow-sm rounded-3 border-0"
        style="background-color: #1e293b; color:white;">
        Konfirmasi
    </button>

</form>

@endsection