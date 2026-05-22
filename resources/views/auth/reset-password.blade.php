@extends('layouts.guest')

@section('content')

<div class="text-center mb-4">
    <h3 class="fw-bold">Reset Password 🔑</h3>
    <p class="text-muted small">
        Masukkan password baru kamu
    </p>
</div>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ request()->route('token') }}">

    <!-- EMAIL -->
    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Email</label>
        <input type="email" name="email"
            class="form-control"
            value="{{ request()->email }}"
            required>
    </div>

    <!-- PASSWORD -->
    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Password Baru</label>
        <input type="password" name="password"
            class="form-control"
            required>
    </div>

    <!-- KONFIRMASI -->
    <div class="mb-3 text-start">
        <label class="form-label small fw-bold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation"
            class="form-control"
            required>
    </div>

    <button type="submit"
        class="btn w-100 py-2 fw-bold shadow-sm rounded-3 border-0"
        style="background-color: #1e293b; color:white;">
        Reset Password
    </button>

</form>

@endsection