<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectOrder;
use App\Models\ProjectOrderItem;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $projectOrder = ProjectOrder::with(['project', 'items'])->where('is_confirmed', false)->get();
        // $projectIds = ProjectOrderItem::query()
        //     ->distinct()
        //     ->pluck('project_id');

        // $projects = Project::with('prospect.quotations.items.product')
        //     ->whereIn('id', $projectIds)
        //     ->get();

        return view('project-order.index', compact('projectOrder'));
    }

    public function Financeindex(Request $request)
    {
        if ($request->has('calculate_percentage') && $request->has('project_id')) {
            $projectId = $request->get('project_id');
            $percentage = $this->calculateDeliveryPercentage($projectId);

            return response()->json(['percentage' => $percentage]);
        }

        $projectOrder = ProjectOrder::where('is_confirmed', true)->pluck('project_id');
        $projects = Project::with('prospect.quotations.items.product')
            ->whereIn('id', $projectOrder)
            ->get();

        return view('finance-project-order.index', compact('projects'));
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

        $existingOrders = ProjectOrderItem::where('project_id', $projectId)
            ->get()
            ->keyBy('quotation_item_id');

        $quotationItems = collect([]);

        if ($project->prospect && $project->prospect->quotations) {
            foreach ($project->prospect->quotations as $quotation) {
                foreach ($quotation->items as $item) {
                    $existingOrder = $existingOrders->get($item->id);
                    $currentStockUsed = $existingOrder ? $existingOrder->stock_used : 0;
                    $remainingQty = $item->quantity - $currentStockUsed;

                    // Determine status based on stock usage
                    $status = 'pending';
                    if ($existingOrder) {
                        if ($currentStockUsed >= $item->quantity) {
                            $status = 'complete';
                        } elseif ($currentStockUsed > 0) {
                            $status = 'partial';
                        } else {
                            $status = 'pending';
                        }
                    }

                    $quotationItems->push([
                        'id' => $item->id,
                        'project_id' => $projectId,
                        'product_id' => $item->product_id,
                        'brand' => $item->product->brand ?? '-',
                        'model_type' => $item->product->type ?? $item->product->name ?? '-',
                        'qty_needed' => $item->quantity,
                        'qty_ready' => $currentStockUsed,
                        'remaining_qty' => max(0, $remainingQty),
                        'qty' => $item->quantity,
                        'unit' => 'unit',
                        'stok' => $item->product->stock->stock_quantity ?? 0,
                        'stock_used_so_far' => $currentStockUsed,
                        'existing_ead' => $existingOrder && $existingOrder->estimated_arrival_date ? $existingOrder->estimated_arrival_date->format('Y-m-d') : '',
                        'ead' => '-',
                        'status' => $status,
                    ]);
                }
            }
        }

        $orders = $quotationItems;

        return DataTables::of($orders)
            ->addColumn('ead', function ($row) {
                return $row['existing_ead'] ?: '-';
            })
            ->addColumn('status', function ($row) {
                $status = $row['status'];
                $statusClass = match ($status) {
                    'complete' => 'bg-success',
                    'partial' => 'bg-warning',
                    'pending' => 'bg-secondary',
                    default => 'bg-info'
                };

                $statusText = match ($status) {
                    'complete' => 'Ready stok',
                    'partial' => 'Order sebagian',
                    'pending' => 'Indent',
                    default => ucfirst($status)
                };

                return '<span class="badge '.$statusClass.' text-white">'.$statusText.'</span>';
            })
            ->addColumn('bobot', function ($row) {
                $percentage = 0;
                if ($row['qty'] > 0) {
                    $percentage = round(($row['stock_used_so_far'] / $row['qty']) * 100);
                }

                return $percentage.'%';
            })
            ->addColumn('action', function ($row) {
                $productName = $row['brand'].' '.$row['model_type'];
                $isComplete = $row['status'] === 'complete';
                $buttonClass = $isComplete ? 'btn-success' : 'btn-primary';
                $buttonText = $isComplete ? 'View' : 'Manage';
                $buttonIcon = $isComplete
                    ? '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />'
                    : '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" />';

                return '<button type="button" class="btn btn-sm '.$buttonClass.' btn-manage-stock" 
                        data-id="'.$row['id'].'" 
                        data-project-id="'.$row['project_id'].'" 
                        data-brand="'.$row['brand'].'" 
                        data-model="'.$row['model_type'].'" 
                        data-product="'.htmlspecialchars($productName).'" 
                        data-stock="'.$row['stok'].'" 
                        data-qty="'.$row['qty_needed'].'" 
                        data-qty-ready="'.$row['qty_ready'].'" 
                        data-qty-remaining="'.$row['remaining_qty'].'" 
                        data-stock-used="'.$row['stock_used_so_far'].'" 
                        data-ead="'.$row['existing_ead'].'" 
                        data-is-complete="'.($isComplete ? '1' : '0').'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            '.$buttonIcon.'
                        </svg>
                        '.$buttonText.'
                    </button>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function financeDatatable(Request $request)
    {
        $projectId = $request->get('project_id');

        if (! $projectId) {
            return DataTables::of(collect([]))->make(true);
        }

        // Check if project order is confirmed
        $projectOrder = ProjectOrder::where('project_id', $projectId)
            ->where('is_confirmed', true)
            ->first();

        if (! $projectOrder) {
            return DataTables::of(collect([]))->make(true);
        }

        // Get project order items directly
        $projectOrderItems = ProjectOrderItem::where('project_id', $projectId)
            ->with(['product.stock', 'quotationItem'])
            ->get();

        $quotationItems = collect([]);

        foreach ($projectOrderItems as $orderItem) {
            $product = $orderItem->product;
            $quotationItem = $orderItem->quotationItem;
            $currentStockUsed = $orderItem->stock_used;
            $requiredQty = $orderItem->required_qty;
            $remainingQty = $requiredQty - $currentStockUsed;

            $status = 'pending';
            if ($currentStockUsed >= $requiredQty) {
                $status = 'complete';
            } elseif ($currentStockUsed > 0) {
                $status = 'partial';
            }

            $quotationItems->push([
                'id' => $quotationItem->id ?? $orderItem->id,
                'project_id' => $projectId,
                'product_id' => $orderItem->product_id,
                'brand' => $product->brand ?? '-',
                'model_type' => $product->type ?? $product->name ?? '-',
                'qty' => $requiredQty,
                'unit' => 'unit',
                'stok' => $product->stock->stock_quantity ?? 0,
                'stock_used_so_far' => $currentStockUsed,
                'remaining_qty' => max(0, $remainingQty),
                'existing_ead' => $orderItem->estimated_arrival_date?->format('Y-m-d') ?? '',
                'ead' => '-',
                'status' => $status,
                'po_number' => $orderItem->po_number ?? '',
                'po_file_path' => $orderItem->po_file_path ?? '',
            ]);
        }

        // Filter only items with 'partial' or 'pending' status
        $orders = $quotationItems->filter(function ($item) {
            return in_array($item['status'], ['partial', 'pending']);
        });

        return DataTables::of($orders)
            ->addColumn('ead', function ($row) {
                return $row['existing_ead'] ?: '-';
            })
            ->addColumn('status', function ($row) {
                $status = $row['status'];
                $statusClass = match ($status) {
                    'complete' => 'bg-success',
                    'partial' => 'bg-warning',
                    'pending' => 'bg-secondary',
                    default => 'bg-info'
                };

                $statusText = match ($status) {
                    'complete' => 'Ready stok',
                    'partial' => 'Order sebagian',
                    'pending' => 'Indent',
                    default => ucfirst($status)
                };

                return '<span class="badge '.$statusClass.' text-white">'.$statusText.'</span>';
            })
            ->addColumn('bobot', function ($row) {
                $percentage = 0;
                if ($row['qty'] > 0) {
                    $percentage = round(($row['stock_used_so_far'] / $row['qty']) * 100);
                }

                return $percentage.'%';
            })
            ->addColumn('action', function ($row) {
                // Hide button if PO file already exists
                if (! empty($row['po_file_path'])) {
                    return '-';
                }

                $isComplete = $row['status'] === 'complete';

                // Status badge for display
                $statusBadge = match ($row['status']) {
                    'complete' => '<span class="badge bg-success text-white">Ready stok</span>',
                    'partial' => '<span class="badge bg-warning text-white">Order sebagian</span>',
                    'pending' => '<span class="badge bg-secondary text-white">Indent</span>',
                    default => '<span class="badge bg-info text-white">'.ucfirst($row['status']).'</span>'
                };

                // Get PO file name from path
                $poFileName = $row['po_file_path'] ? basename($row['po_file_path']) : '';

                // Manage PO button
                $buttonClass = $isComplete ? 'btn-success' : 'btn-primary';
                $buttonText = $isComplete ? 'View PO' : (! empty($row['po_number']) ? 'Edit' : 'Manage PO');
                $buttonIcon = $isComplete
                    ? '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />'
                    : '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" />';

                return '<button type="button" class="btn btn-sm '.$buttonClass.' btn-manage-po" 
                        data-id="'.$row['id'].'" 
                        data-project-id="'.$row['project_id'].'" 
                        data-brand="'.$row['brand'].'" 
                        data-model="'.$row['model_type'].'" 
                        data-required="'.$row['qty'].'" 
                        data-confirmed="'.$row['stock_used_so_far'].'" 
                        data-to-order="'.$row['remaining_qty'].'"
                        data-status="'.htmlspecialchars($statusBadge).'"
                        data-po-number="'.$row['po_number'].'"
                        data-po-file="'.$row['po_file_path'].'"
                        data-po-filename="'.$poFileName.'"
                        data-eta="'.$row['existing_ead'].'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            '.$buttonIcon.'
                        </svg>
                        '.$buttonText.'
                    </button>';
            })
            ->rawColumns(['status', 'action'])
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

        // Get all delivery orders for this project with their items
        $deliveryOrders = \App\Models\DeliveryOrder::where('project_id', $projectId)
            ->with('items')
            ->get();

        // Create a map of product_id => total delivered quantity
        $deliveredQtyMap = [];
        foreach ($deliveryOrders as $do) {
            foreach ($do->items as $item) {
                $productId = $item->product_id;
                if (! isset($deliveredQtyMap[$productId])) {
                    $deliveredQtyMap[$productId] = 0;
                }
                $deliveredQtyMap[$productId] += $item->qty;
            }
        }

        $deliveryItems = collect([]);

        if ($project->prospect && $project->prospect->quotations) {
            foreach ($project->prospect->quotations as $quotation) {
                foreach ($quotation->items as $item) {
                    $requiredQty = $item->quantity;
                    $deliveredQty = $deliveredQtyMap[$item->product_id] ?? 0;

                    // Determine delivery status
                    if ($deliveredQty >= $requiredQty) {
                        $status = 'Lengkap';
                        $statusClass = 'bg-success';
                    } elseif ($deliveredQty > 0 && $deliveredQty < $requiredQty) {
                        $status = 'Parsial';
                        $statusClass = 'bg-warning';
                    } else {
                        $status = 'Belum dikirim';
                        $statusClass = 'bg-secondary';
                    }

                    $deliveryItems->push([
                        'id' => $item->id,
                        'brand' => $item->product->brand ?? '-',
                        'model_type' => $item->product->type ?? $item->product->name ?? '-',
                        'qty' => $requiredQty,
                        'delivered' => $deliveredQty,
                        'remaining' => max(0, $requiredQty - $deliveredQty),
                        'status' => $status,
                        'status_class' => $statusClass,
                    ]);
                }
            }
        }

        return DataTables::of($deliveryItems)
            ->addColumn('status', function ($row) {
                return '<span class="badge '.$row['status_class'].' text-white">'.$row['status'].'</span>';
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
                $projectOrder = ProjectOrder::find($projectId);

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
                        'project_order_id' => $projectOrder->id,
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
     * Confirm project order and update is_confirmed status
     */
    public function confirm(string $projectId)
    {
        try {
            $projectOrder = ProjectOrder::where('project_id', $projectId)->firstOrFail();

            $projectOrder->update(['is_confirmed' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed successfully. Finance team has been notified.',
            ]);
        } catch (\Exception $e) {
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
     * Upload PO file and number for a project order item
     */
    public function uploadPO(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:quotation_items,id',
            'po_number' => 'required|string|max:255',
            'estimated_arrival_date' => 'required|date',
            'po_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            $quotationItemId = $request->order_item_id;
            $poNumber = $request->po_number;

            // Find the project order item
            $orderItem = ProjectOrderItem::where('quotation_item_id', $quotationItemId)->firstOrFail();

            // Handle file upload if present
            $poFilePath = $orderItem->po_file_path;
            if ($request->hasFile('po_file')) {
                // Delete old file if exists
                if ($poFilePath && Storage::disk('public')->exists($poFilePath)) {
                    Storage::disk('public')->delete($poFilePath);
                }

                // Store new file
                $file = $request->file('po_file');
                $fileName = time().'_'.$poNumber.'.'.$file->getClientOriginalExtension();
                $poFilePath = $file->storeAs('po_files', $fileName, 'public');
            }

            // Update order item
            $orderItem->update([
                'po_number' => $poNumber,
                'po_file_path' => $poFilePath,
                'estimated_arrival_date' => $request->estimated_arrival_date,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Purchase order has been saved successfully',
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
