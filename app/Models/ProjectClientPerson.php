<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectClientPerson extends Model
{
    protected $table = 'project_client_people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'name',
        'email',
        'phone',
        'note',
    ];

    /**
     * Get the project that owns the client person.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
