<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectOrderItem;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectOrderController extends Controller
{
    public function index(Request $request)
    {
        // Check if AJAX request for percentage calculation
        if ($request->has('calculate_percentage') && $request->has('project_id')) {
            $projectId = $request->get('project_id');
            $percentage = $this->calculateDeliveryPercentage($projectId);

            return response()->json(['percentage' => $percentage]);
        }

        $projects = Project::with('prospect.quotations.items.product')
            ->get();

        return view('project-order.index', compact('projects'));
    }

    /**
     * Calculate delivery percentage for a project
     */
    private function calculateDeliveryPercentage($projectId)
    {
        $project = Project::with('prospect.quotations.items')->find($projectId);

        if (! $project || ! $project->prospect || ! $project->prospect->quotations) {
            return 0;
        }

        $totalRequired = 0;
        $totalDelivered = 0;

        foreach ($project->prospect->quotations as $quotation) {
            foreach ($quotation->items as $item) {
                $totalRequired += $item->quantity;

                $orderItem = ProjectOrderItem::where('project_id', $projectId)
                    ->where('quotation_item_id', $item->id)
                    ->first();

                if ($orderItem) {
                    $totalDelivered += $orderItem->stock_used;
                }
            }
        }

        if ($totalRequired == 0) {
            return 0;
        }

        return round(($totalDelivered / $totalRequired) * 100, 1);
    }

    public function datatable(Request $request)
    {
        $projectId = $request->get('project_id');

        if (! $projectId) {
            return DataTables::of(collect([]))->make(true);
        }

        $project = Project::with('prospect.quotations.items.product.stock')
            ->findOrFail($projectId);

        // Get existing project order items
        $existingOrders = ProjectOrderItem::where('project_id', $projectId)
            ->get()
            ->keyBy('quotation_item_id');

        $quotationItems = collect([]);

        if ($project->prospect && $project->prospect->quotations) {
            foreach ($project->prospect->quotations as $quotation) {
                foreach ($quotation->items as $item) {
                    $existingOrder = $existingOrders->get($item->id);

                    // Skip jika sudah complete (stock_used >= required_qty)
                    if ($existingOrder && $existingOrder->stock_used >= $existingOrder->required_qty) {
                        continue;
                    }

                    $currentStockUsed = $existingOrder ? $existingOrder->stock_used : 0;
                    $remainingQty = $item->quantity - $currentStockUsed;

                    $quotationItems->push([
                        'id' => $item->id,
                        'project_id' => $projectId,
                        'product_id' => $item->product_id,
                        'brand' => $item->product->brand ?? '-',
                        'model_type' => $item->product->type ?? $item->product->name ?? '-',
                        'qty' => $item->quantity,
                        'unit' => 'unit',
                        'stok' => $item->product->stock->stock_quantity ?? 0,
                        'stock_used_so_far' => $currentStockUsed,
                        'remaining_qty' => $remainingQty,
                        'existing_ead' => $existingOrder && $existingOrder->estimated_arrival_date ? $existingOrder->estimated_arrival_date->format('Y-m-d') : '',
                        'ead' => '-',
                        'status' => $existingOrder ? $existingOrder->order_status : 'pending',
                    ]);
                }
            }
        }

        $orders = $quotationItems;

        return DataTables::of($orders)
            ->addColumn('stok_digunakan', function ($row) {
                $maxAllowed = min($row['remaining_qty'], $row['stok']);

                return '<input type="number" class="form-control form-control-sm stok-digunakan-input" 
                        data-id="'.$row['id'].'" 
                        data-qty="'.$row['remaining_qty'].'" 
                        data-stok="'.$row['stok'].'" 
                        min="0" 
                        max="'.$maxAllowed.'" 
                        value="0" 
                        placeholder="0" 
                        style="width: 100px;">';
            })
            ->addColumn('ead_input', function ($row) {
                $existingEad = $row['existing_ead'] ?? '';

                return '<input type="date" class="form-control form-control-sm ead-input" 
                        data-id="'.$row['id'].'" 
                        value="'.$existingEad.'" 
                        style="width: 150px;">';
            })
            ->rawColumns(['stok_digunakan', 'ead_input'])
            ->make(true);
    }

    public function deliveryDatatable(Request $request)
    {
        $projectId = $request->get('project_id');

        if (! $projectId) {
            return DataTables::of(collect([]))->make(true);
        }

        $project = Project::with('prospect.quotations.items.product')
            ->findOrFail($projectId);

        // Get existing project order items
        $existingOrders = ProjectOrderItem::where('project_id', $projectId)
            ->get()
            ->keyBy('quotation_item_id');

        $deliveryItems = collect([]);

        if ($project->prospect && $project->prospect->quotations) {
            foreach ($project->prospect->quotations as $quotation) {
                foreach ($quotation->items as $item) {
                    $existingOrder = $existingOrders->get($item->id);
                    
                    // Determine status
                    $status = 'pending';
                    $stockUsed = 0;
                    $ead = '-';
                    
                    if ($existingOrder) {
                        $status = $existingOrder->order_status;
                        $stockUsed = $existingOrder->stock_used;
                        $ead = $existingOrder->estimated_arrival_date 
                            ? $existingOrder->estimated_arrival_date->format('Y-m-d') 
                            : '-';
                    }

                    $deliveryItems->push([
                        'id' => $item->id,
                        'brand' => $item->product->brand ?? '-',
                        'model_type' => $item->product->type ?? $item->product->name ?? '-',
                        'qty' => $item->quantity,
                        'delivered' => $stockUsed,
                        'ead' => $ead,
                        'status' => $status,
                    ]);
                }
            }
        }

        return DataTables::of($deliveryItems)
            ->addColumn('status', function ($row) {
                $statusClass = match ($row['status']) {
                    'complete' => 'bg-success',
                    'partial' => 'bg-warning',
                    'pending' => 'bg-secondary',
                    'proses' => 'bg-info',
                    default => 'bg-info'
                };

                return '<span class="badge '.$statusClass.' text-white">'.ucfirst($row['status']).'</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
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
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'items' => 'required|array',
            'items.*.quotation_item_id' => 'required|exists:quotation_items,id',
            'items.*.stock_used' => 'required|integer|min:0',
            'items.*.estimated_arrival_date' => 'nullable|date',
        ]);

        // Additional validation: EAD required when stock_used > 0
        foreach ($request->items as $item) {
            if ($item['stock_used'] > 0 && empty($item['estimated_arrival_date'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estimation Arrival Date is required when stock is used',
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            $projectId = $request->project_id;
            $items = $request->items;

            foreach ($items as $item) {
                if ($item['stock_used'] <= 0) {
                    continue;
                }

                $quotationItem = QuotationItem::with('product.stock')->findOrFail($item['quotation_item_id']);
                $productStock = $quotationItem->product->stock;
                $requiredQty = $quotationItem->quantity;

                if (! $productStock) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Product stock not found for item ID: '.$item['quotation_item_id'],
                    ], 400);
                }

                // Check existing order
                $existingOrder = ProjectOrderItem::where('project_id', $projectId)
                    ->where('quotation_item_id', $item['quotation_item_id'])
                    ->first();

                $currentStockUsed = $existingOrder ? $existingOrder->stock_used : 0;
                $newStockUsed = $currentStockUsed + $item['stock_used'];

                // Validate tidak melebihi required qty
                if ($newStockUsed > $requiredQty) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Total stock used cannot exceed required quantity ('.$requiredQty.') for product: '.$quotationItem->product->name,
                    ], 400);
                }

                // Validate stok tersedia cukup
                if ($productStock->stock_quantity < $item['stock_used']) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for product: '.$quotationItem->product->name,
                    ], 400);
                }

                // Tentukan status order
                $orderStatus = 'pending';
                if ($newStockUsed >= $requiredQty) {
                    $orderStatus = 'complete';
                } elseif ($newStockUsed > 0) {
                    $orderStatus = 'partial';
                }

                // Create or update project order item
                if ($existingOrder) {
                    $existingOrder->update([
                        'stock_used' => $newStockUsed,
                        'estimated_arrival_date' => $item['estimated_arrival_date'],
                        'order_status' => $orderStatus,
                    ]);
                } else {
                    ProjectOrderItem::create([
                        'project_id' => $projectId,
                        'quotation_item_id' => $item['quotation_item_id'],
                        'product_id' => $quotationItem->product_id,
                        'required_qty' => $requiredQty,
                        'stock_used' => $item['stock_used'],
                        'estimated_arrival_date' => $item['estimated_arrival_date'],
                        'order_status' => $orderStatus,
                    ]);
                }

                // Reduce product stock
                $productStock->decrement('stock_quantity', $item['stock_used']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed successfully and stock updated',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
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
