@extends('layouts.guest')

@section('content')

<div class="text-center mb-4">
    <h3 class="fw-bold">Verifikasi Email 📧</h3>
    <p class="text-muted small">
        Silakan cek email kamu dan klik link verifikasi
    </p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success text-center">
        Link verifikasi baru sudah dikirim ke email kamu
    </div>
@endif

<div class="text-center mb-4">
    <small class="text-muted">
        Belum menerima email?
    </small>
</div>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf

    <button type="submit"
        class="btn w-100 py-2 fw-bold shadow-sm rounded-3 border-0 mb-2"
        style="background-color: #1e293b; color:white;">
        Kirim Ulang Email Verifikasi
    </button>
</form>

<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit"
        class="btn btn-outline-danger w-100 py-2 fw-bold rounded-3">
        Logout
    </button>
</form>

@endsection