<?php

namespace App\Models;

use App\Models\User;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    /**
     * Calculate grand total price for quotation items.
     *
     * @return array{
     *     subtotal_price: float,
     *     ppn_price: float,
     *     grand_total_price: float,
     *     ppn_percentage: int,
     *     items_count: int,
     *     formatted: array{
     *         subtotal_price: string,
     *         ppn_price: string,
     *         grand_total_price: string
     *     }
     * }
     */
    public function calculateGrandTotalPrice(): array
    {
        $quotationItemsGrouped = collect();

        if ($this->items && $this->items->isNotEmpty()) {
            $quotationItemsGrouped = $this->items
                ->groupBy('product_id')
                ->map(function ($items) {
                    $firstItem = $items->first();
                    return [
                        'product' => $firstItem->product,
                        'total_qty' => $items->sum('quantity'),
                        'items' => $items,
                    ];
                });
        }

        $total = 0;

        foreach ($quotationItemsGrouped as $groupedItem) {
            $pricePerUnit = $groupedItem['product']->price ?? 0;
            $itemTotal = $groupedItem['total_qty'] * $pricePerUnit;
            $total += $itemTotal;
        }

        $grandTotal = $total;

        return [
            'subtotal_price' => $total,
            'grand_total_price' => $grandTotal,
            'ppn_percentage' => 11,
            'items_count' => $quotationItemsGrouped->count(),
            'formatted' => [
                'subtotal_price' => 'Rp ' . number_format($total, 0, ',', '.'),
                'grand_total_price' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
            ],
        ];
    }

    /**
     * Calculate grand total base price for quotation items.
     *
     * @return array{
     *     subtotal_base_price: float,
     *     ppn_base_price: float,
     *     grand_total_base_price: float,
     *     ppn_percentage: int,
     *     items_count: int,
     *     formatted: array{
     *         subtotal_base_price: string,
     *         ppn_base_price: string,
     *         grand_total_base_price: string
     *     }
     * }
     */

    //calculate omset
    


    public function calculateGrandTotalBasePrice(): array
    {
        $quotationItemsGrouped = collect();

        if ($this->items && $this->items->isNotEmpty()) {
            $quotationItemsGrouped = $this->items
                ->groupBy('product_id')
                ->map(function ($items) {
                    $firstItem = $items->first();
                    return [
                        'product' => $firstItem->product,
                        'total_qty' => $items->sum('quantity'),
                        'items' => $items,
                    ];
                });
        }

        $totalDasar = 0;

        foreach ($quotationItemsGrouped as $groupedItem) {
            $basePricePerUnit = $groupedItem['product']->base_price_rupiah_for_luar_negeri ?? 0;
            $itemTotalDasar = $groupedItem['total_qty'] * $basePricePerUnit;
            $totalDasar += $itemTotalDasar;
        }

        $grandTotalDasar = $totalDasar;

        return [
            'subtotal_base_price' => $totalDasar,
            'grand_total_base_price' => $grandTotalDasar,
            'ppn_percentage' => 11,
            'items_count' => $quotationItemsGrouped->count(),
            'formatted' => [
                'subtotal_base_price' => 'Rp ' . number_format($totalDasar, 0, ',', '.'),
                'grand_total_base_price' => 'Rp ' . number_format($grandTotalDasar, 0, ',', '.'),
            ],
        ];
    }

    /**
     * Calculate comprehensive pricing including both regular and base prices.
     *
     * @return array{
     *     regular: array,
     *     base: array,
     *     items_grouped: \Illuminate\Support\Collection,
     *     comparison: array
     * }
     */
    public function calculateComprehensivePricing(): array
    {
        $quotationItemsGrouped = collect();

        if ($this->items && $this->items->isNotEmpty()) {
            $quotationItemsGrouped = $this->items
                ->groupBy('product_id')
                ->map(function ($items) {
                    $firstItem = $items->first();
                    return [
                        'product' => $firstItem->product,
                        'total_qty' => $items->sum('quantity'),
                        'items' => $items,
                    ];
                });
        }

        // Calculate regular prices
        $regularTotal = 0;
        foreach ($quotationItemsGrouped as $groupedItem) {
            $pricePerUnit = $groupedItem['product']->price ?? 0;
            $itemTotal = $groupedItem['total_qty'] * $pricePerUnit;
            $regularTotal += $itemTotal;
        }

        // Calculate base prices
        $baseTotal = 0;
        foreach ($quotationItemsGrouped as $groupedItem) {
            $basePricePerUnit = $groupedItem['product']->base_price_rupiah_for_luar_negeri ?? 0;
            $itemTotalDasar = $groupedItem['total_qty'] * $basePricePerUnit;
            $baseTotal += $itemTotalDasar;
        }

        // Calculate PPN and grand totals
        $regularPPN = $regularTotal * 0.11;
        $regularGrandTotal = $regularTotal + $regularPPN;
        
        $basePPN = $baseTotal * 0.11;
        $baseGrandTotal = $baseTotal + $basePPN;

        // Calculate profit margin
        $profitAmount = $regularTotal - $baseTotal;
        $profitPercentage = $baseTotal > 0 ? ($profitAmount / $baseTotal) * 100 : 0;

        return [
            'regular' => [
                'subtotal' => $regularTotal,
                'ppn' => $regularPPN,
                'grand_total' => $regularGrandTotal,
                'formatted' => [
                    'subtotal' => 'Rp ' . number_format($regularTotal, 0, ',', '.'),
                    'ppn' => 'Rp ' . number_format($regularPPN, 0, ',', '.'),
                    'grand_total' => 'Rp ' . number_format($regularGrandTotal, 0, ',', '.'),
                ],
            ],
            'base' => [
                'subtotal' => $baseTotal,
                'ppn' => $basePPN,
                'grand_total' => $baseGrandTotal,
                'formatted' => [
                    'subtotal' => 'Rp ' . number_format($baseTotal, 0, ',', '.'),
                    'ppn' => 'Rp ' . number_format($basePPN, 0, ',', '.'),
                    'grand_total' => 'Rp ' . number_format($baseGrandTotal, 0, ',', '.'),
                ],
            ],
            'items_grouped' => $quotationItemsGrouped,
            'comparison' => [
                'profit_amount' => $profitAmount,
                'profit_percentage' => round($profitPercentage, 2),
                'ppn_percentage' => 11,
                'items_count' => $quotationItemsGrouped->count(),
                'formatted' => [
                    'profit_amount' => 'Rp ' . number_format($profitAmount, 0, ',', '.'),
                    'profit_percentage' => number_format($profitPercentage, 2) . '%',
                ],
            ],
        ];
    }

    /**
     * Get monthly omset data for a specific year and user
     *
     * @param int $year
     * @param int|null $userId
     * @return array
     */
    public static function getMonthlyOmsetData(int $year, ?int $userId = null): array
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $query = self::where('status', 'accepted')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
                
            if ($userId) {
                $query->where('created_by', $userId);
            }
            
            $quotations = $query->with(['items.product'])->get();
            
            $monthlyOmset = 0;
            $monthlyBaseOmset = 0;
            $monthlyGrossProfit = 0;
            
            foreach ($quotations as $quotation) {
                $pricing = $quotation->calculateComprehensivePricing();
                $monthlyOmset += $pricing['regular']['grand_total'];
                $monthlyBaseOmset += $pricing['base']['grand_total'];
                $monthlyGrossProfit += $pricing['comparison']['profit_amount'];
            }
            
            $monthlyData[$month] = [
                'omset' => $monthlyOmset,
                'base_omset' => $monthlyBaseOmset,
                'gross_profit' => $monthlyGrossProfit,
                'quotation_count' => $quotations->count(),
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Get monthly acceptance rate data for a specific year and user
     *
     * @param int $year
     * @param int|null $userId
     * @return array
     */
    public static function getMonthlyAcceptanceRateData(int $year, ?int $userId = null): array
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $totalQuery = self::whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
                
            $acceptedQuery = self::where('status', 'accepted')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
                
            if ($userId) {
                $totalQuery->where('created_by', $userId);
                $acceptedQuery->where('created_by', $userId);
            }
            
            $totalCount = $totalQuery->count();
            $acceptedCount = $acceptedQuery->count();
            
            $acceptanceRate = $totalCount > 0 ? round(($acceptedCount / $totalCount) * 100) : 0;
            
            $monthlyData[$month] = [
                'total_quotations' => $totalCount,
                'accepted_quotations' => $acceptedCount,
                'acceptance_rate' => $acceptanceRate,
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Get comprehensive monthly data combining omset and acceptance rates
     *
     * @param int $year
     * @param int|null $userId
     * @return array
     */
    public static function getComprehensiveMonthlyData(int $year, ?int $userId = null): array
    {
        $omsetData = self::getMonthlyOmsetData($year, $userId);
        $acceptanceData = self::getMonthlyAcceptanceRateData($year, $userId);
        
        $comprehensiveData = [
            'omset' => [],
            'base_omset' => [],
            'gross_profit' => [],
            'target_completion' => [],
            'quotation_counts' => [],
        ];
        
        for ($month = 1; $month <= 12; $month++) {
            $comprehensiveData['omset'][] = $omsetData[$month]['omset'];
            $comprehensiveData['base_omset'][] = $omsetData[$month]['base_omset'];
            $comprehensiveData['gross_profit'][] = $omsetData[$month]['gross_profit'];
            $comprehensiveData['target_completion'][] = $acceptanceData[$month]['acceptance_rate'];
            $comprehensiveData['quotation_counts'][] = $acceptanceData[$month]['total_quotations'];
        }
        
        return $comprehensiveData;
    }

    /**
     * Get monthly target achievement based on sales target
     *
     * @param int $year
     * @param int $userId
     * @param float|null $monthlyTarget
     * @return array
     */
    public static function getMonthlyTargetAchievement(int $year, int $userId, ?float $monthlyTarget = null): array
    {
        $omsetData = self::getMonthlyOmsetData($year, $userId);
        $achievementData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyOmset = $omsetData[$month]['base_omset']; // Use base price for target calculation
            
            if ($monthlyTarget && $monthlyTarget > 0) {
                $achievementPercentage = round(($monthlyOmset / $monthlyTarget) * 100, 1);
            } else {
                $achievementPercentage = 0;
            }
            
            $achievementData[$month] = [
                'omset' => $monthlyOmset,
                'target' => $monthlyTarget ?? 0,
                'achievement_percentage' => $achievementPercentage,
            ];
        }
        
        return $achievementData;
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
