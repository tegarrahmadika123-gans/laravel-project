<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
    'user_id',
    'book_id',
    'npm',
    'email',
    'tanggal_pinjam',
    'tanggal_kembali',
    'status',
    'verification_code',
    'return_verification_code', // Tambahkan ini
    'denda', 'denda_tambahan',
    'payment_status',
    'rejection_reason'
];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}