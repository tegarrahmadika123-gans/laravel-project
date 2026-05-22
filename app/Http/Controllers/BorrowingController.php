<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrowing;
use App\Models\Book;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    // ✅ Tambahkan type int pada $id
    public function store(Request $request, int $id)
{
    $request->validate([
        'npm' => 'required',
        'email' => 'required|email',
        'tempo' => 'required|integer'
    ]);

    $book = Book::findOrFail($id);

    // CEK apakah user masih meminjam buku dengan judul yang sama
    $sudahPinjamJudulSama = Borrowing::where('user_id', Auth::id())
        ->whereIn('status', ['pending', 'dipinjam'])
        ->whereHas('book', function ($query) use ($book) {
            $query->where('judul', $book->judul);
        })
        ->exists();

    if ($sudahPinjamJudulSama) {
        return back()->with(
            'error',
            'Anda sudah meminjam buku dengan judul yang sama.'
        );
    }

    $punyaDenda = Borrowing::where('user_id', Auth::id())
        ->where('payment_status', 'belum_lunas')
        ->where(function ($q) {
            $q->where('denda', '>', 0)
              ->orWhere('denda_tambahan', '>', 0);
        })
        ->exists();



if ($punyaDenda) {
    return back()->with(
        'error',
        'Anda masih memiliki denda yang belum dilunasi. Lunasi denda terlebih dahulu.'
    );
}
$jumlahPinjam = Borrowing::where('user_id', Auth::id())
    ->where('status', 'dipinjam')
    ->count();

if ($jumlahPinjam >= 3) {
    return back()->with(
        'error',
        'Maksimal peminjaman adalah 3 buku.'
    );
}
        if ($book->stok <= 0) {
            return back()->with('error', 'Stok habis');
        }

        $kode = random_int(100000, 999999);

        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'npm' => $request->npm,
            'email' => $request->email,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays((int)$request->tempo),
            'status' => 'pending',
            'payment_status' => 'lunas',
            'verification_code' => $kode
        ]);

        return back()->with('return_code', $kode);
    }

    public function index()
    {
        /** @var \App\Models\User $user */ // 💡 Memberitahu VS Code bahwa $user adalah model User
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            // 💡 Gunakan array untuk multiple relations agar Intelephense tidak protes
            $borrowings = Borrowing::with(['user', 'book'])->latest()->get();
        } else {
            $borrowings = Borrowing::with('book')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        foreach ($borrowings as $b) {
            $dendaTelat = $b->denda ?? 0;

            if ($b->status == 'dipinjam') {
                if (now()->gt($b->tanggal_kembali)) {
                    $selisihJam = Carbon::parse($b->tanggal_kembali)->diffInHours(now());
                    $hariTerlambat = ceil($selisihJam / 24) ?: 1;
                    $dendaTelat = max(0, $hariTerlambat * 2000);
                } else {
                    $dendaTelat = 0;
                }
            }
            $b->total_denda = max(0, $dendaTelat) + ($b->denda_tambahan ?? 0);
        }

        return view('borrowings.index', compact('borrowings'));
    }

    public function verify(Request $request, int $id)
    {
        $request->validate(['kode' => 'required']);

        $data = Borrowing::findOrFail($id);

        if ($data->status != 'pending') {
            return back()->with('error', 'Sudah diverifikasi!');
        }

        if ($data->verification_code != $request->kode) {
            return back()->with('error', 'Kode salah!');
        }

        if ($data->book->stok <= 0) {
            return back()->with('error', 'Stok habis!');
        }

        $data->update(['status' => 'dipinjam']);
        $data->book->decrement('stok');

        return back()->with('success', 'Peminjaman disetujui');
    }

    public function requestReturn(int $id) 
    {
        $data = Borrowing::findOrFail($id);
        if ($data->user_id != Auth::id()) {
    abort(403);
}
        $kode = random_int(100000, 999999);
        $data->update(['return_verification_code' => $kode]);
        
        return back()->with('return_code', $kode);
    }

    public function verifyReturn(Request $request, int $id) 
    {
        $data = Borrowing::findOrFail($id);
        
        if ($data->return_verification_code != $request->kode) {
            return back()->with('error', 'Kode verifikasi salah!');
        }

        $tglKembali = Carbon::parse($data->tanggal_kembali);
        $sekarang = Carbon::now();
        $dendaTelat = 0;
        
        
        if ($sekarang->gt($tglKembali)) {
            $selisihJam = $tglKembali->diffInHours($sekarang);
            $hariTerlambat = ceil($selisihJam / 24) ?: 1;
            $dendaTelat = max(0, $hariTerlambat * 2000);
        }

        $totalDenda =
    $dendaTelat + ($request->denda_tambahan ?? 0);

$data->update([
    'status' => 'kembali',

    'denda' => ($data->denda ?? 0) + $dendaTelat,

    'denda_tambahan' =>
        ($data->denda_tambahan ?? 0)
        + ($request->denda_tambahan ?? 0),

    'payment_status' =>
        $totalDenda > 0 ? 'belum_lunas' : 'lunas'
]);

        $data->book->increment('stok');
        return back()->with('success', 'Buku berhasil dikembalikan!');
    }

    public function update(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') return back();

        $request->validate([
    'tanggal_kembali' => 'required|date',
    'denda_tambahan' => 'nullable|integer|min:0',
    'payment_status' => 'required|in:lunas,belum_lunas'
]);

        $data = Borrowing::findOrFail($id);
        $tglKembali = Carbon::parse($request->tanggal_kembali);
        $sekarang = Carbon::now();
        $dendaTelat = 0;

        if ($sekarang->gt($tglKembali)) {
            $selisihJam = $tglKembali->diffInHours($sekarang);
            $hariTerlambat = ceil($selisihJam / 24) ?: 1;
            $dendaTelat = max(0, $hariTerlambat * 2000);
        }

        $data->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'denda' => $dendaTelat,
            'denda_tambahan' => $request->denda_tambahan ?? 0,
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'Data berhasil diperbarui!');
    }
    // ❌ TOLAK PEMINJAMAN
public function reject(Request $request, int $id)
{
    $request->validate([
        'reason' => 'required'
    ]);

    $data = Borrowing::findOrFail($id);

    if ($data->status !== 'pending') {
        return back()->with('error', 'Peminjaman sudah diproses');
    }

    $data->update([
        'status' => 'ditolak',
        'rejection_reason' => $request->reason
    ]);

    return back()->with('success', 'Peminjaman berhasil ditolak');
}
public function resendCode(int $id)
{
    $data = Borrowing::findOrFail($id);

    if ($data->status != 'pending') {
        return back()->with('error', 'Kode hanya bisa dikirim ulang saat pending');
    }

    $newCode = random_int(100000, 999999);

    $data->update([
        'verification_code' => $newCode
    ]);

    return back()->with([
        'success' => 'Kode berhasil diperbarui',
        'return_code' => $newCode
    ]);
}
public function pay(int $id)
{
    $data = Borrowing::findOrFail($id);

    if ($data->status != 'kembali') {
        return back()->with('error', 'Buku belum dikembalikan');
    }

    $data->update([
        'payment_status' => 'lunas'
    ]);

    return back()->with(
        'success',
        'Denda berhasil dilunasi'
    );
}
}