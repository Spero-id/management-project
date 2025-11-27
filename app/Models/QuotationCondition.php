<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationCondition extends Model
{
    protected $table = 'quotation_conditions';

    protected $fillable = [
        'condition',
    ];
}
