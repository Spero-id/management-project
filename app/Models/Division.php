<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'name',
        'kode',
        'is_generate_sales_quotation_number'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
