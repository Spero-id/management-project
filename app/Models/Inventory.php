<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item',
        'stock_awal',
        'unit_awal',
        'stock_akhir',
        'unit_akhir',
        'note',
        'posisi',
    ];

    protected $casts = [
        'stock_awal' => 'integer',
        'stock_akhir' => 'integer',
    ];
}
