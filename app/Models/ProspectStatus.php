<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectStatus extends Model
{
    protected $table = 'prospect_status';
    
    protected $fillable = [
        'name',
        'persentage',
        'color'
    ];
}
