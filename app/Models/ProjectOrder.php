<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectOrder extends Model
{
    protected $table = 'project_orders';
    protected $fillable = [
        'project_id',
        'is_confirmed',
        'is_order_confirmed',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function items()
    {
        return $this->hasMany(ProjectOrderItem::class, 'project_order_id');
    }
}
