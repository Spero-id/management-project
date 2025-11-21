<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectWeeklyMeeting extends Model
{
    protected $fillable = [
        'project_id',
        'task',
        'petugas',
        'start_date',
        'end_date',
        'target_date',
        'notes',
        'progress',
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
