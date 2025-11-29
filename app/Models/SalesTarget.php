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
            'target_gross_profit' => 'integer',
            'target_monthly' => 'integer',
            'target_yearly' => 'integer',
            'year' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
