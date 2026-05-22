<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\EbookController; // ✅ TAMBAHAN
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\AdminMembershipController;




/*
|--------------------------------------------------------------------------
| REDIRECT AWAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| 🔑 RESET PASSWORD CUSTOM (TANPA EMAIL)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'reset'])
        ->name('password.update.manual');
});


/*
|--------------------------------------------------------------------------
| ROUTE PERPUSTAKAAN
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| ROUTE PERPUSTAKAAN (LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 📚 SEMUA USER (BUKU FISIK)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/borrowings', [BorrowingController::class, 'index']); // List Peminjaman
    
    // Alur Pinjam
    Route::post('/borrow/{id}', [BorrowingController::class, 'store']);
    
    // Alur Kembali Tahap 1: User minta kode
    Route::post('/return/request/{id}', [BorrowingController::class, 'requestReturn']);

    // 📘 E-BOOK
    Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
    Route::post('/ebooks/read/{id}', [EbookController::class, 'read']);
    Route::post('/ebooks/download/{id}', [EbookController::class, 'download']);
    Route::get('/membership', [MembershipController::class, 'index']);
    Route::post('/membership/store',[MembershipController::class, 'store'])->name('membership.store');
});

    // 🔐 KHUSUS ADMIN
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Verifikasi & Management Peminjaman
        Route::post('/verify/{id}', [BorrowingController::class, 'verify']); // Verif Pinjam
        Route::post('/return/verify/{id}', [BorrowingController::class, 'verifyReturn']); // Verif Kembali (Tahap 2)
        Route::post('/borrowing/update/{id}', [BorrowingController::class, 'update']); // Edit Tanggal & Denda Rusak
        Route::post('/borrow/reject/{id}', [BorrowingController::class, 'reject']);
        Route::post('/borrow/resend/{id}', [BorrowingController::class, 'resendCode']);
        Route::post('/borrow/pay/{id}', [BorrowingController::class, 'pay']);
        // Manajemen Buku (CRUD)
        Route::get('/books/create', [BookController::class, 'create']);
        Route::post('/books/store', [BookController::class, 'store']);
        Route::get('/books/{id}/edit', [BookController::class, 'edit']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);

        // EBOOK (UPLOAD)
        Route::post('/ebooks/store', [EbookController::class, 'store']);
        Route::post('/ebooks/update/{id}', [EbookController::class, 'update']);
        Route::delete('/ebooks/delete/{id}', [EbookController::class, 'destroy']);


        // MEMBERSHIP ADMIN
Route::get(
    '/admin/memberships',
    [AdminMembershipController::class, 'index']
);

Route::post(
    '/admin/memberships/{id}/approve',
    [AdminMembershipController::class, 'approve']
);

Route::post(
    '/admin/memberships/{id}/reject',
    [AdminMembershipController::class, 'reject']
);
        });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| AUTH BAWAAN
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';