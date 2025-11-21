<?php

namespace App\Http\Controllers;

use App\Exports\StockExport;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('stock.index');
    }

    /**
     * Get stock data for DataTables
     */
    public function datatable()
    {
        $products = Product::with('stock')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'brand' => $product->brand,
                    'type' => $product->type,
                    'stok' => $product->stock?->stock_quantity ?? 0,
                ];
            });

        return DataTables::of($products)
            ->make(true);
    }

    /**
     * Export stock data to Excel
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new StockExport(), 'stock-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Get unique brands from products
     */
    public function getBrands(Request $request): JsonResponse
    {
        $search = $request->get('search', '');

        $brands = Product::query()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->when($search, function ($query, $search) {
                $query->where('brand', 'LIKE', "%{$search}%");
            })
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        return response()->json($brands);
    }

    /**
     * Get unique types from products filtered by brand
     */
    public function getTypes(Request $request): JsonResponse
    {
        $brand = $request->get('brand');
        $search = $request->get('search', '');

        $types = Product::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->when($brand, function ($query, $brand) {
                $query->where('brand', $brand);
            })
            ->when($search, function ($query, $search) {
                $query->where('type', 'LIKE', "%{$search}%");
            })
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        return response()->json($types);
    }

    /**
     * Get current stock quantity for a product
     */
    public function getCurrentStock(Request $request): JsonResponse
    {
        $brand = $request->get('brand');
        $type = $request->get('type');

        if (!$brand || !$type) {
            return response()->json(['stock' => 0]);
        }

        $product = Product::where('brand', $brand)
            ->where('type', $type)
            ->first();

        if (!$product) {
            return response()->json(['stock' => 0]);
        }

        $stock = ProductStock::where('product_id', $product->id)->first();

        return response()->json([
            'stock' => $stock ? $stock->stock_quantity : 0,
            'product_id' => $product->id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find or create product
            $product = Product::firstOrCreate(
                [
                    'brand' => $request->brand,
                    'type' => $request->type,
                ],
                [
                    'name' => $request->brand . ' - ' . $request->type,
                    'description' => 'Auto-generated from stock input',
                    'price' => 0,
                ]
            );

            // Find or create stock
            $stock = ProductStock::firstOrNew(['product_id' => $product->id]);
            $stock->stock_quantity = ($stock->stock_quantity ?? 0) + $request->stock_quantity;
            $stock->save();

            DB::commit();

            return response()->json([
                'message' => 'Stock berhasil ditambahkan',
                'data' => [
                    'product' => $product,
                    'stock' => $stock
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
