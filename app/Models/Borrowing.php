<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'no_peminjaman',
        'keperluan',
        'penanggung_jawab',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'string',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BorrowingItems::class);
    }
}
