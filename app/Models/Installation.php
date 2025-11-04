<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'proportional'
    ];

    protected $casts = [
        'proportional' => 'decimal:2'
    ];
}
