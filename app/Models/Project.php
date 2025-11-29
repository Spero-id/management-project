<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{


    protected $table = 'projects';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name',
        'client_email', 
        'client_phone',
        'company',
        'company_identity',
        'project_name',
        'no_po',
        'description',
        'created_by',
        'prospect_id',
        'execution_time',
        'po_file',
        'spk_file'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function projectOrder()
    {
        return $this->hasOne(ProjectOrder::class, 'project_id');
    }

    

    /**
     * Get the project manager.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    /**
     * Get the project status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ProspectStatus::class, 'status_id');
    }

    /**
     * Get the prospect that was converted to this project.
     */
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Prospect::class, 'prospect_id');
    }

    /**
     * Get the client persons related to the project.
     */
    public function clientPersons(): HasMany
    {
        return $this->hasMany(\App\Models\ProjectClientPerson::class, 'project_id');
    }

    /**
     * Get the WBS items related to the project.
     */
    public function wbsItems(): HasMany
    {
        return $this->hasMany(\App\Models\ProjectWBSItem::class, 'project_id');
    }

    /**
     * Get the order items related to the project.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(\App\Models\ProjectOrderItem::class, 'project_id');
    }

    /**
     * Get the minutes of meetings for this project.
     */
    public function minuteOfMeetings()
    {
        return $this->morphMany(\App\Models\MinuteOfMeeting::class, 'noteable');
    }
}
