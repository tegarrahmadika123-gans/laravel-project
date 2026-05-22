<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [

        'user_id',

        'paket',

        'harga',

        'durasi_hari',

        'payment_method',

        'payment_proof',

        'payment_status',

        'started_at',

        'expired_at'

    ];

    protected $casts = [

        'started_at' => 'datetime',

        'expired_at' => 'datetime'

    ];

    // relasi ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}