<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'prospect_id',
        'created_by',
        'quotation_number',
        'revision_number',
        'total_amount',
        'status',
        'notes',
        'need_accommodation',
        'installation_percentage',
        'accommodation_wilayah',
        'accommodation_hotel_rooms',
        'accommodation_people',
        'accommodation_target_days',
        'accommodation_plane_ticket_price',
        'accommodation_total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'need_accommodation' => 'boolean',
        'installation_percentage' => 'decimal:2',
        'accommodation_total_amount' => 'decimal:2',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function accommodationItems()
    {
        return $this->hasMany(QuotationAccommodationItem::class);
    }

    public function installationItems()
    {
        return $this->hasMany(QuotationInstallationItem::class);
    }

    public function calculateTotal()
    {
        $productsTotal = $this->items->sum('subtotal');
        $installationsTotal = $this->installationItems->sum('subtotal');
        $accommodationsTotal = $this->accommodationItems->sum('unit_price');
        $this->total_amount = $productsTotal + $installationsTotal + $accommodationsTotal;
        $this->save();
    }

    /**
     * Generate quotation number
     */
    public function generateQuotationNumber($isNewQuotation = true)
    {
        $prospect = $this->prospect ?: Prospect::find($this->prospect_id);

        $sales = User::find($this->created_by);

        // Generate roman month
        $months = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanMonth = $months[intval(date('n'))];

        // Format sales number with padding
        $salesNumber = $sales->no_quotation;
        if ($salesNumber < 10) {
            $salesNumber = '0'.$salesNumber;
        }

        // Get increment number
        if ($isNewQuotation) {
            $incrementNumber = $sales->quotationCount() + 1;
        } else {
            // Extract from existing quotation number
            $salesQuotationCount = preg_split('/[-\/]/', explode('.', $this->quotation_number)[1])[0];
            $incrementNumber = intval($salesQuotationCount);
        }

        $salesNumber .= '.'.str_pad($incrementNumber, 3, '0', STR_PAD_LEFT);

        // Add revision suffix
        $revisionSuffix = '-'.str_pad($this->revision_number ?: 0, 2, '0', STR_PAD_LEFT);

        return 'SISC/'.$prospect->company_identity.'/Q-'.$salesNumber.$revisionSuffix.'/'.$romanMonth.'/'.date('Y');
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($quotation) {
        //     if (! $quotation->quotation_number) {
        //         $quotation->quotation_number = $quotation->generateQuotationNumber(true);
        //     }
        // });

        static::deleting(function ($quotation) {
            // Delete all quotation items explicitly (though cascade constraint should handle this)
            $quotation->items()->delete();
            $quotation->installationItems()->delete();
        });
    }
}
