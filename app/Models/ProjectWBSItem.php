<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectWBSItem extends Model
{
    protected $table = 'project_wbs_items';

    protected $guarded = ['id'];

    protected $fillable = [
        'project_id',
        'parent_id',
        'title',
        'type',
        'from',
        'to',
        'quantity',
        'item_type',
        'is_done',
        'note',
        'level'
    ];

    /**
     * Get all children of this WBS item.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ProjectWBSItem::class, 'parent_id')->orderBy('id');
    }

    /**
     * Get the parent of this WBS item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectWBSItem::class, 'parent_id');
    }
}
