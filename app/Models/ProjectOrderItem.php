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
        'delivery_qty',
        'estimated_arrival_date',
        'order_status',
        'po_number',
        'po_file_path',
        "project_order_id",
        'delivery_qty',
        'remaining_qty',
    ];

    protected $casts = [
        'stock_used' => 'integer',
        'delivery_qty' => 'integer',
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
