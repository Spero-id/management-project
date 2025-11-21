<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'brand',
        'type',
        'stok_tersedia',
        'jumlah_barang',
        'jumlah_dikembalikan',
    ];

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }
}
