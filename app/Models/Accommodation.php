<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];
}
