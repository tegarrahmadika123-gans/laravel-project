@extends('layouts.app')

@section('content')

<div class="profile-wrap">

    {{-- HEADER --}}
    <div class="profile-header">

        <div>
            <h2>👤 Profile Saya</h2>
            <p>Kelola informasi akun dan keamanan profile</p>
        </div>

        <div class="profile-badge">
            {{ auth()->user()->role }}
        </div>

    </div>

    <div class="profile-grid">

        {{-- PROFILE INFO --}}
        <div class="profile-card">

            <div class="card-title">
                Informasi Akun
            </div>

            <form method="POST" action="{{ route('profile.update') }}">

                @csrf
                @method('PATCH')

                <div class="mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $user->name) }}"
                           required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>

                </div>

                <button class="save-btn">

                    💾 Simpan Perubahan

                </button>

            </form>

        </div>

        {{-- ACCOUNT INFO --}}
        <div class="profile-card">

            <div class="card-title">
                Detail Akun
            </div>

            <div class="info-box">

                <span>Status Akun</span>

                <strong class="text-success">
                    Aktif
                </strong>

            </div>

            <div class="info-box">

                <span>Role</span>

                <strong>
                    {{ ucfirst(auth()->user()->role) }}
                </strong>

            </div>

            <div class="info-box">

                <span>Bergabung</span>

                <strong>
                    {{ auth()->user()->created_at->format('d M Y') }}
                </strong>

            </div>

        </div>

    </div>

</div>

<style>

body{
    background:#f1f5f9;
    font-family:'Inter',sans-serif;
}

.profile-wrap{
    max-width:1200px;
    margin:auto;
    padding:28px;
}

.profile-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}

.profile-header h2{
    font-weight:800;
    color:#0f172a;
    margin-bottom:4px;
}

.profile-header p{
    color:#64748b;
    margin:0;
}

.profile-badge{
    background:linear-gradient(135deg,#4f46e5,#6366f1);
    color:white;
    padding:10px 18px;
    border-radius:16px;
    font-weight:700;
    box-shadow:0 10px 20px rgba(79,70,229,.2);
}

.profile-grid{
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:24px;
}

.profile-card{
    background:white;
    border-radius:28px;
    padding:28px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.card-title{
    font-size:1rem;
    font-weight:800;
    margin-bottom:24px;
    color:#0f172a;
}

.form-label{
    font-weight:700;
    font-size:.85rem;
    color:#334155;
    margin-bottom:8px;
}

.form-control{
    border-radius:16px;
    border:1px solid #e2e8f0;
    padding:14px;
    background:#f8fafc;
}

.form-control:focus{
    border-color:#6366f1;
    box-shadow:none;
    background:white;
}

.save-btn{
    width:100%;
    border:none;
    background:linear-gradient(135deg,#4f46e5,#6366f1);
    color:white;
    padding:14px;
    border-radius:16px;
    font-weight:700;
    transition:.2s;
}

.save-btn:hover{
    transform:translateY(-2px);
}

.info-box{
    background:#f8fafc;
    border-radius:18px;
    padding:18px;
    margin-bottom:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.info-box span{
    color:#64748b;
    font-weight:600;
}

.info-box strong{
    color:#0f172a;
}

@media(max-width:768px){

    .profile-grid{
        grid-template-columns:1fr;
    }

    .profile-header{
        flex-direction:column;
        align-items:flex-start;
        gap:16px;
    }

}

</style>

@endsection