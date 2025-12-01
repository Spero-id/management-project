<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('inventory.index');
    }

    /**
     * Get inventory data for DataTables
     */
    public function datatable()
    {
        $inventories = Inventory::query();

        return DataTables::of($inventories)
            ->addColumn('action', function ($row) {
                $editBtn = '<button type="button" class="btn btn-icon btn-ghost-primary edit-btn me-1" data-id="'.$row->id.'" aria-label="Edit" title="Edit inventory">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                </button>';

                $deleteBtn = '<button type="button" class="btn btn-icon btn-ghost-danger delete-btn" data-id="'.$row->id.'" data-item="'.$row->item.'" aria-label="Delete" title="Delete inventory">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>';

                return $editBtn . $deleteBtn;
            })
            ->addColumn('satuan_awal', function ($row) {
                return $row->unit_awal;
            })
            ->addColumn('satuan_akhir', function ($row) {
                return $row->unit_akhir;
            })
            ->editColumn('note', function ($row) {
                return $row->note ? \Illuminate\Support\Str::limit($row->note, 50) : '-';
            })
            ->rawColumns(['action', 'note'])
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
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'stock_awal' => 'required|integer|min:0',
            'unit_awal' => 'required|string|max:50',
            'stock_akhir' => 'required|integer|min:0',
            'unit_akhir' => 'required|string|max:50',
            'note' => 'nullable|string',
            'posisi' => 'required|string|max:255',
        ]);

        $inventory = Inventory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inventory created successfully',
            'data' => $inventory,
        ], 201);
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
        $inventory = Inventory::findOrFail($id);

        return response()->json($inventory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'stock_awal' => 'required|integer|min:0',
            'unit_awal' => 'required|string|max:50',
            'stock_akhir' => 'required|integer|min:0',
            'unit_akhir' => 'required|string|max:50',
            'note' => 'nullable|string',
            'posisi' => 'required|string|max:255',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inventory updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventory deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting inventory: ' . $e->getMessage(),
            ], 500);
        }
    }
}
