<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItems;
use App\Models\Project;
use App\Models\ProjectOrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
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
        return view('delivery-order.index');
    }

    public function getProjects(Request $request): JsonResponse
    {
        $search = $request->get('q', '');

        $projects = \App\Models\ProjectOrder::with('project')
            ->where('is_confirmed', true)
            ->whereHas('project', function ($query) use ($search) {
                if ($search) {
                    $query->where('project_name', 'LIKE', "%{$search}%");
                }
            })
            ->get()
            ->map(function ($projectOrder) {
                return [
                    'id' => $projectOrder->project_id,
                    'text' => $projectOrder->project->project_name ?? 'N/A',
                ];
            })
            ->unique('id')
            ->values();

        return response()->json($projects);
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
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary btn-view-do" data-id="'.$deliveryOrder->id.'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                            View
                        </button>
                        <a href="'.route('delivery-order.pdf', $deliveryOrder->id).'" target="_blank" class="btn btn-sm btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 9l1 0" />
                                <path d="M9 13l6 0" />
                                <path d="M9 17l6 0" />
                            </svg>
                            PDF
                        </a>
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
            // 'items' => 'required|array|min:1',
            // 'items.*.quotation_item_id' => 'required|exists:quotation_items,id',
            // 'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.qty' => 'required|integer|min:1',
            // 'items.*.sn' => 'nullable|array',
            // 'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
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
                $serialNumbers = isset($item['sn']) && is_array($item['sn'])
                    ? array_values(array_filter($item['sn'], fn ($sn) => ! empty($sn)))
                    : null;

                $projectOrderItem = ProjectOrderItem::where('project_id', $request->project_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                $newDeliveredQty = ($projectOrderItem->delivery_qty ?? 0) + $item['qty'];

                $projectOrderItem->update([
                    'delivery_qty' => $newDeliveredQty,
                    'stock_used' => 0,
                ]);

                DeliveryOrderItems::create([
                    'delivery_order_id' => $deliveryOrder->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'sn' => ! empty($serialNumbers) ? $serialNumbers : null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery order created successfully',
                'data' => $deliveryOrder->load('items.product'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create delivery order',
                'error' => $e->getMessage(),
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
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch delivery order',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function getProjectItems(int $projectId): JsonResponse
    {

        try {
            $project = Project::with(['prospect.quotations.items.product'])
                ->findOrFail($projectId);

            $deliveryOrders = DeliveryOrder::where('project_id', $projectId)
                ->with('items')
                ->get();

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

            $quotationItems = collect();

            if ($project->prospect && $project->prospect->quotations->isNotEmpty()) {
                $projectOrderItems = ProjectOrderItem::with('product')
                    ->where('project_id', $project->id)
                    ->get();

                if ($projectOrderItems->isNotEmpty()) {
                    $quotationItems = $projectOrderItems->map(function ($item) use ($deliveredQtyMap) {
                        // $requiredQty = $item->quantity;
                        $deliveredQty = $deliveredQtyMap[$item->product_id] ?? 0;
                        $remainingQty = max(0, $item->required_qty - $deliveredQty);

                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'N/A',
                            'brand' => $item->product->brand ?? 'N/A',
                            'model_type' => $item->product->type ?? 'N/A',
                            'quantity' => $item->required_qty,

                            'delivered_qty' => $deliveredQty,
                            'stock_used' => $item->stock_used ?? 0,
                            'remaining_qty' => $remainingQty,
                            'unit' => 'pcs',
                        ];
                    });
                    // ->filter(function ($item) {
                    //     return $item['remaining_qty'] > 0;
                    // })->values();

                }

            }

            return response()->json([
                'success' => true,
                'data' => [
                    'project' => [
                        'id' => $project->id,
                        'name' => $project->project_name,
                    ],
                    'items' => $quotationItems,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch project items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function streamPdf(int $id)
    {
        try {
            $deliveryOrder = DeliveryOrder::with(['project', 'items.product'])
                ->findOrFail($id);

            $pdf = Pdf::loadView('delivery-order.pdf', [
                'deliveryOrder' => $deliveryOrder,
            ]);

            return $pdf->stream('DO-'.$deliveryOrder->do_number.'.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
