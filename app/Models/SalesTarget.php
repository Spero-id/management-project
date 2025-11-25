<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class SalesTarget extends Model
{
    protected $fillable = [
        'user_id',
        'target_gross_profit',
        'target_monthly',
        'target_yearly',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'target_gross_profit' => 'decimal:2',
            'target_monthly' => 'decimal:2',
            'target_yearly' => 'decimal:2',
            'year' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
