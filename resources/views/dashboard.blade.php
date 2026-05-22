@extends('layouts.app')

@section('content')

<div class="dash-wrap">

    {{-- HEADER --}}
    <div class="dash-header">

        <div>
            <h2>📊 Dashboard Admin</h2>
            <p>Realtime statistik LibraSpace</p>
        </div>

        <div class="live-badge">
            ● LIVE
        </div>

    </div>

    {{-- MINI STATS --}}
    <div class="mini-stats">

        <div class="mini-card">
            <span>🌐 Visitor</span>
            <h3>{{ $visitorCount }}</h3>
        </div>

        <div class="mini-card">
            <span>📚 Buku</span>
            <h3>{{ $totalBooks }}</h3>
        </div>

        <div class="mini-card">
            <span>📖 E-Book</span>
            <h3>{{ $totalEbooks }}</h3>
        </div>

        <div class="mini-card">
            <span>👥 User</span>
            <h3>{{ $totalUsers }}</h3>
        </div>

        <div class="mini-card">
            <span>🔄 Dipinjam</span>
            <h3>{{ $borrowedBooks }}</h3>
        </div>

        <div class="mini-card">
            <span>⚠ Stok</span>
            <h3>{{ $criticalStock }}</h3>
        </div>

        <div class="mini-card">
            <span>💰 Denda</span>
            <h3>Rp {{ number_format($totalDenda,0,',','.') }}</h3>
        </div>

        <div class="mini-card">
            <span>👑 Member</span>
            <h3>Rp {{ number_format($membershipIncome,0,',','.') }}</h3>
        </div>

    </div>

    {{-- MAIN GRID --}}
    <div class="main-grid">

        {{-- LEFT --}}
        <div class="grid-column">

            {{-- EBOOK READ --}}
            <div class="compact-box">

                <div class="box-head">
                    🔥 E-Book Dibaca
                </div>

                @forelse($topReadEbooks as $ebook)

                    <div class="compact-item">

                        <div class="item-left">

                            <strong>
                                {{ $ebook->ebook->judul ?? '-' }}
                            </strong>

                            <small>
                                {{ $ebook->total }}x dibaca
                            </small>

                        </div>

                        <div class="rank">
                            #{{ $loop->iteration }}
                        </div>

                    </div>

                @empty

                    <div class="empty">
                        Belum ada data
                    </div>

                @endforelse

            </div>

            {{-- TOP BORROW --}}
            <div class="compact-box">

                <div class="box-head">
                    📚 Buku Terlaris
                </div>

                @forelse($topBorrowedBooks as $book)

                    <div class="compact-item">

                        <div class="item-left">

                            <strong>
                                {{ $book->book->judul ?? '-' }}
                            </strong>

                            <small>
                                {{ $book->total }}x dipinjam
                            </small>

                        </div>

                        <div class="rank yellow">
                            #{{ $loop->iteration }}
                        </div>

                    </div>

                @empty

                    <div class="empty">
                        Belum ada data
                    </div>

                @endforelse

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="grid-column">

            {{-- DOWNLOAD --}}
            <div class="compact-box">

                <div class="box-head">
                    ⬇ E-Book Download
                </div>

                @forelse($topDownloadEbooks as $ebook)

                    <div class="compact-item">

                        <div class="item-left">

                            <strong>
                                {{ $ebook->ebook->judul ?? '-' }}
                            </strong>

                            <small>
                                {{ $ebook->total }}x download
                            </small>

                        </div>

                        <div class="rank blue">
                            #{{ $loop->iteration }}
                        </div>

                    </div>

                @empty

                    <div class="empty">
                        Belum ada data
                    </div>

                @endforelse

            </div>

            {{-- VISITOR --}}
            <div class="compact-box">

                <div class="box-head">
                    🌐 Visitor Hari Ini
                </div>

                <div class="visitor-flex">

                    <div class="visitor-mini">
                        <span>Total</span>
                        <h4>{{ $visitorCount }}</h4>
                    </div>

                    <div class="visitor-mini">
                        <span>Hari Ini</span>
                        <h4>{{ $todayVisitors }}</h4>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="chart-card mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold mb-0">
            📈 Statistik Peminjaman
        </h5>

        <small class="text-muted">
            7 Hari Terakhir
        </small>

    </div>

    <canvas id="borrowChart"></canvas>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('borrowChart');
    
    // Ambil data dari PHP
    const labels = [
        @foreach($borrowChart as $item)
            "{{ \Carbon\Carbon::parse($item->date)->format('d M') }}", 
        @endforeach
    ];

    const dataPoints = [
        @foreach($borrowChart as $item)
            {{ $item->total }},
        @endforeach
    ];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Peminjaman',
                data: dataPoints,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 5, // Biar grafik nggak gepeng kalau datanya sedikit
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
<style>
/* 1. Base Setup */
/* =========================
   GLOBAL
========================= */

body{
    background:
        radial-gradient(circle at top left,#eef4ff,#f8fbff 45%,#f5f7fb 100%);
    font-family:'Inter',sans-serif;
    color:#0f172a;
}

/* WRAPPER */
.dash-wrap{
    padding:24px;
    max-width:1450px;
    margin:auto;
}

/* =========================
   HEADER CONTAINER
========================= */

.dash-header{
    position:relative;
    overflow:hidden;

    display:flex;
    justify-content:space-between;
    align-items:center;

    padding:26px 30px;
    margin-bottom:24px;

    border-radius:28px;

    background:
        linear-gradient(135deg,#1e293b,#334155);

    box-shadow:
        0 15px 40px rgba(15,23,42,.12);

    transition:.3s ease;
}

.dash-header::before{
    content:'';
    position:absolute;

    width:260px;
    height:260px;

    background:rgba(255,255,255,.05);

    border-radius:50%;

    top:-100px;
    right:-70px;
}

.dash-header:hover{
    transform:translateY(-3px);
}

.dash-header h2{
    font-size:1.7rem;
    font-weight:800;
    color:white;
    margin-bottom:4px;
}

.dash-header p{
    margin:0;
    color:rgba(255,255,255,.72);
    font-size:.92rem;
}

/* LIVE */
.live-badge{
    background:rgba(255,255,255,.12);

    backdrop-filter:blur(14px);

    border:1px solid rgba(255,255,255,.1);

    color:white;

    padding:10px 18px;

    border-radius:16px;

    font-size:.76rem;
    font-weight:800;

    letter-spacing:.5px;

    animation:pulse 2s infinite;

    transition:.25s ease;
}

.live-badge:hover{
    transform:scale(1.05);
}

/* =========================
   MINI STATS
========================= */

.mini-stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:24px;
}

/* CARD */
.mini-card{
    position:relative;
    overflow:hidden;

    background:rgba(255,255,255,.72);

    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.6);

    border-radius:24px;

    padding:22px;

    box-shadow:
        0 10px 30px rgba(15,23,42,.05);

    transition:.28s ease;
}

.mini-card::before{
    content:'';
    position:absolute;
    left:0;
    top:0;

    width:5px;
    height:100%;

    background:
        linear-gradient(180deg,#6366f1,#818cf8);
}

/* VARIASI */
.mini-card:nth-child(2)::before{
    background:linear-gradient(180deg,#3b82f6,#60a5fa);
}

.mini-card:nth-child(3)::before{
    background:linear-gradient(180deg,#8b5cf6,#a78bfa);
}

.mini-card:nth-child(5)::before{
    background:linear-gradient(180deg,#f59e0b,#fbbf24);
}

.mini-card:nth-child(7)::before{
    background:linear-gradient(180deg,#10b981,#34d399);
}

.mini-card:hover{
    transform:translateY(-5px);

    box-shadow:
        0 18px 40px rgba(15,23,42,.08);
}

.mini-card span{
    display:block;

    font-size:.74rem;
    font-weight:700;

    letter-spacing:.4px;
    text-transform:uppercase;

    color:#64748b;
}

.mini-card h3{
    margin-top:10px;

    font-size:1.45rem;
    font-weight:800;

    color:#0f172a;
}

/* =========================
   GRID
========================= */

.main-grid{
    display:grid;
    grid-template-columns:1.15fr .85fr;
    gap:22px;
}

.grid-column{
    display:flex;
    flex-direction:column;
    gap:22px;
}

/* =========================
   BOX
========================= */

.compact-box{
    background:rgba(255,255,255,.72);

    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.55);

    border-radius:28px;

    padding:24px;

    box-shadow:
        0 10px 35px rgba(15,23,42,.05);

    transition:.3s ease;
}

.compact-box:hover{
    transform:translateY(-4px);

    box-shadow:
        0 18px 45px rgba(15,23,42,.08);
}

/* TITLE */
.box-head{
    font-size:1rem;
    font-weight:800;

    margin-bottom:18px;

    color:#0f172a;
}

/* ITEM */
.compact-item{
    background:
        linear-gradient(to right,#f8fbff,#f1f5f9);

    border:1px solid #edf2f7;

    border-radius:18px;

    padding:14px 16px;

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:12px;

    transition:.25s ease;
}

.compact-item:hover{
    transform:translateX(4px);
    background:#ffffff;
}

/* TEXT */
.item-left strong{
    display:block;

    font-size:.88rem;
    color:#0f172a;
}

.item-left small{
    color:#64748b;
    font-size:.76rem;
}

/* RANK */
.rank{
    background:#eef2ff;
    color:#4338ca;

    padding:7px 12px;

    border-radius:12px;

    font-size:.72rem;
    font-weight:800;
}

.rank.yellow{
    background:#fef3c7;
    color:#b45309;
}

.rank.blue{
    background:#dbeafe;
    color:#1d4ed8;
}

/* EMPTY */
.empty{
    color:#94a3b8;
    font-size:.85rem;
}

/* =========================
   VISITOR
========================= */

.visitor-flex{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
}

.visitor-mini{
    background:
        linear-gradient(135deg,#eff6ff,#dbeafe);

    border:1px solid #bfdbfe;

    border-radius:20px;

    padding:20px;

    text-align:center;

    transition:.25s ease;
}

.visitor-mini:hover{
    transform:translateY(-4px);
}

.visitor-mini span{
    font-size:.76rem;
    color:#64748b;
}

.visitor-mini h4{
    margin-top:8px;

    font-size:1.5rem;
    font-weight:800;

    color:#1d4ed8;
}

/* =========================
   CHART CARD
========================= */

.chart-card{
    background:rgba(255,255,255,.74);

    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.6);

    border-radius:28px;

    padding:28px;

    margin-top:24px;

    box-shadow:
        0 12px 35px rgba(15,23,42,.05);

    transition:.3s ease;
}

.chart-card:hover{
    transform:translateY(-4px);

    box-shadow:
        0 18px 45px rgba(15,23,42,.08);
}

.chart-card h5{
    color:#0f172a;
    font-weight:800;
}

#borrowChart{
    height:330px !important;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:1100px){

    .mini-stats{
        grid-template-columns:repeat(2,1fr);
    }

    .main-grid{
        grid-template-columns:1fr;
    }

}

@media(max-width:768px){

    .dash-wrap{
        padding:14px;
    }

    .dash-header{
        flex-direction:column;
        align-items:flex-start;
        gap:18px;
    }

    .mini-stats{
        grid-template-columns:1fr;
    }

    .visitor-flex{
        grid-template-columns:1fr;
    }

}

/* =========================
   ANIMATION
========================= */

@keyframes pulse{

    0%{
        opacity:1;
    }

    50%{
        opacity:.55;
    }

    100%{
        opacity:1;
    }

}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection