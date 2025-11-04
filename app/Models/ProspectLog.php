<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectLog extends Model
{
    protected $fillable = [
        'prospect_id',
        'from_status',
        'to_status',
        'note',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fromStatus()
    {
        return $this->belongsTo(ProspectStatus::class, 'from_status');
    }

    public function toStatus()
    {
        return $this->belongsTo(ProspectStatus::class, 'to_status');
    }
}
