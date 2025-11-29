<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MinuteOfMeeting extends Model
{
    protected $table = 'minute_of_meetings';

    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent noteable model (project, etc.).
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created this minute of meeting.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
