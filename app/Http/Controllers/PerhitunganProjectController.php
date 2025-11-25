<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectOrderItem;
use Illuminate\Http\Request;

class PerhitunganProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with([
            'orderItems.product',
            'orderItems.quotationItem.product'
        ])
        ->whereHas('orderItems', function ($query) {
            $query->whereHas('quotationItem');
        })
        ->get()
        ->map(function ($project) {
            // Group items by quotation items
            $project->quotationItemsGrouped = $project->orderItems
                ->filter(fn($item) => $item->quotationItem !== null)
                ->groupBy('quotation_item_id')
                ->map(function ($items, $quotationItemId) {
                    $quotationItem = $items->first()->quotationItem;
                    $totalQty = $items->sum('required_qty');
                    
                    return [
                        'quotation_item' => $quotationItem,
                        'product' => $quotationItem->product,
                        'total_qty' => $totalQty,
                        'unit_price' => $quotationItem->unit_price,
                        'total_price' => $totalQty * $quotationItem->unit_price,
                    ];
                })
                ->values();
            
            return $project;
        });

        return view('finance-perhitungan-project.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
