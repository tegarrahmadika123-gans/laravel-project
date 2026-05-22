<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookRead extends Model
{
    protected $fillable = [
        'user_id',
        'ebook_id',
        'npm',
        'email',
    ];
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}