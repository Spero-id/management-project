<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectOrderItem extends Model
{
    protected $fillable = [
        'project_id',
        'product_id',
        'quotation_item_id',
        'required_qty',
        'stock_used',
        'estimated_arrival_date',
        'order_status',
        'po_number',
        'po_file_path',
        "project_order_id",
    ];

    protected $casts = [
        'stock_used' => 'integer',
        'estimated_arrival_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function quotationItem(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class);
    }
}
