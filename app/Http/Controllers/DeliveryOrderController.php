<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItems;
use App\Models\Project;
use App\Models\ProjectOrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

final class DeliveryOrderController extends Controller
{
    public function index(): View
    {
        $projects = Project::orderBy('project_name')->get();
        return view('delivery-order.index', compact('projects'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = DeliveryOrder::with(['project', 'items']);

        return DataTables::of($query)
            ->addColumn('project_name', function ($deliveryOrder) {
                return $deliveryOrder->project->project_name ?? 'N/A';
            })
            ->addColumn('items_count', function ($deliveryOrder) {
                return $deliveryOrder->items->count();
            })
            ->editColumn('delivery_date', function ($deliveryOrder) {
                return $deliveryOrder->delivery_date->format('d M Y');
            })
            ->editColumn('created_at', function ($deliveryOrder) {
                return $deliveryOrder->created_at->format('d M Y H:i');
            })
            ->addColumn('action', function ($deliveryOrder) {
                return '
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary btn-view-do" data-id="' . $deliveryOrder->id . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                            View
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'delivery_date' => 'required|date',
            'do_number' => 'required|string|unique:delivery_orders,do_number',
            'items' => 'required|array|min:1',
            'items.*.quotation_item_id' => 'required|exists:quotation_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.sn' => 'nullable|array',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $deliveryOrder = DeliveryOrder::create([
                'project_id' => $request->project_id,
                'delivery_date' => $request->delivery_date,
                'do_number' => $request->do_number,
            ]);

            foreach ($request->items as $item) {
                // Filter out empty serial numbers
                $serialNumbers = isset($item['sn']) && is_array($item['sn']) 
                    ? array_values(array_filter($item['sn'], fn($sn) => !empty($sn)))
                    : null;

                DeliveryOrderItems::create([
                    'delivery_order_id' => $deliveryOrder->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'sn' => !empty($serialNumbers) ? $serialNumbers : null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery order created successfully',
                'data' => $deliveryOrder->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create delivery order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $deliveryOrder = DeliveryOrder::with(['project', 'items.product'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $deliveryOrder->id,
                    'do_number' => $deliveryOrder->do_number,
                    'delivery_date' => $deliveryOrder->delivery_date->format('d M Y'),
                    'project_name' => $deliveryOrder->project->project_name ?? 'N/A',
                    'created_at' => $deliveryOrder->created_at->format('d M Y H:i'),
                    'items' => $deliveryOrder->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->name ?? 'N/A',
                            'brand' => $item->product->brand ?? 'N/A',
                            'model_type' => $item->product->type ?? 'N/A',
                            'qty' => $item->qty,
                            'sn' => $item->sn ?? [],
                            'notes' => $item->notes ?? '-',
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch delivery order',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getProjectItems(int $projectId): JsonResponse
    {
        try {
            $project = Project::with(['prospect.quotations.items.product'])
                ->findOrFail($projectId);

            // Get all delivery orders for this project
            $deliveryOrders = DeliveryOrder::where('project_id', $projectId)
                ->with('items')
                ->get();

            // Create a map of product_id => total delivered quantity
            $deliveredQtyMap = [];
            foreach ($deliveryOrders as $do) {
                foreach ($do->items as $item) {
                    $productId = $item->product_id;
                    if (!isset($deliveredQtyMap[$productId])) {
                        $deliveredQtyMap[$productId] = 0;
                    }
                    $deliveredQtyMap[$productId] += $item->qty;
                }
            }

            $quotationItems = collect();
            
            if ($project->prospect && $project->prospect->quotations->isNotEmpty()) {
                // Get the latest quotation
                $latestQuotation = $project->prospect->quotations()
                    ->latest()
                    ->first();

                if ($latestQuotation) {
                    $quotationItems = $latestQuotation->items->map(function ($item) use ($deliveredQtyMap) {
                        $requiredQty = $item->quantity;
                        $deliveredQty = $deliveredQtyMap[$item->product_id] ?? 0;
                        $remainingQty = max(0, $requiredQty - $deliveredQty);

                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'N/A',
                            'brand' => $item->product->brand ?? 'N/A',
                            'model_type' => $item->product->type ?? 'N/A',
                            'quantity' => $requiredQty,
                            'delivered_qty' => $deliveredQty,
                            'remaining_qty' => $remainingQty,
                            'unit' => 'pcs',
                        ];
                    })->filter(function ($item) {
                        // Only return items that still have remaining qty
                        return $item['remaining_qty'] > 0;
                    })->values();
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'project' => [
                        'id' => $project->id,
                        'name' => $project->project_name,
                    ],
                    'items' => $quotationItems
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch project items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
