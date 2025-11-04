<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationAccommodationItem extends Model
{

    protected $fillable = [
        'quotation_id',
        'name',
        'unit_price'
    ];
}
