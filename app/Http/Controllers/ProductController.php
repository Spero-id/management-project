<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $currencyExchangeRateSetting = Setting::where('setting_name', 'currency_exchange_rate')->first();
        $currencyExchangeRateSettingValue = $currencyExchangeRateSetting ? $currencyExchangeRateSetting->setting_value : 17000;

        return view('product.index', compact('products', 'currencyExchangeRateSettingValue'));
    }

    /**
     * Display a listing of the trashed products.
     */
    public function trashed()
    {
        $products = Product::onlyTrashed()->orderBy('name')->get();

        return view('product.trashed', compact('products'));
    }

    public function dataTableAPI(Request $request)
    {
        $products = Product::query();

        $dataTable = DataTables::of($products)
            ->filter(function ($query) use ($request) {
                // Apply brand filter (exact match)
                if ($request->has('brand') && $request->get('brand') !== null && $request->get('brand') !== '') {
                    $query->where('brand', $request->get('brand'));
                }

                // Apply distributor filter (exact match)
                if ($request->has('distributor_origin') && $request->get('distributor_origin') !== null && $request->get('distributor_origin') !== '') {
                    $query->where('distributor_origin', $request->get('distributor_origin'));
                }

                // Apply global search to name and description if provided by DataTables
                $search = null;
                if (is_array($request->get('search')) && isset($request->get('search')['value'])) {
                    $search = $request->get('search')['value'];
                } elseif ($request->has('search') && ! is_array($request->get('search'))) {
                    $search = $request->get('search');
                }

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    });
                }
            }, true);

        return $dataTable->make(true);
    }

    /**
     * Return distinct brands for select filters.
     */
    public function brands()
    {
        $brands = Product::query()
            ->whereNotNull('brand')
            ->where('brand', '<>', '')
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return response()->json($brands);
    }

    /**
     * Return distinct distributor origins for select filters.
     */
    public function distributors()
    {
        $distributors = Product::query()
            ->whereNotNull('distributor_origin')
            ->where('distributor_origin', '<>', '')
            ->select('distributor_origin')
            ->distinct()
            ->orderBy('distributor_origin')
            ->pluck('distributor_origin');

        return response()->json($distributors);
    }

    /**
     * Return distinct types for select filters.
     */
    public function types(Request $request)
    {
        $brand = $request->get('brand');

        $query = Product::query()
            ->whereNotNull('type')
            ->where('type', '<>', '')
            ->select('type')
            ->distinct()
            ->orderBy('type');

        // Filter by brand if provided
        if ($brand) {
            $query->where('brand', $brand);
        }

        $types = $query->pluck('type');

        return response()->json($types);
    }

    /**
     * Search products for Select2
     */
    public function search(Request $request)
    {
        $term = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Product::query();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%");
            });
        }

        $products = $query->whereNull('deleted_at')
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $products->hasMorePages(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'distributor_origin' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'shipping_fee_by_air' => 'nullable|numeric|min:0',
            'dollar_base_price' => 'nullable|numeric|min:0',
            'margin_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $exchangeSetting = Setting::where('setting_name', 'currency_exchange_rate')->first();
        $exchangeRate = $exchangeSetting ? (float) $exchangeSetting->setting_value : 17000.0;

        $pricing = Product::calculatePricing(
            (float) $validated['dollar_base_price'],
            $exchangeRate,
            (float) $validated['shipping_fee_by_air'],
            (float) $validated['weight'],
            (float) $validated['margin_percentage']
        );

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $pricing['unit_price'],
            'brand' => $validated['brand'],
            'type' => $validated['type'],
            'distributor_origin' => $validated['distributor_origin'],
            'weight' => $validated['weight'],
            'shipping_fee_by_air' => (float) $validated['shipping_fee_by_air'],
            'dollar_base_price' => (float) $validated['dollar_base_price'],
            'base_price_rupiah_for_luar_negeri' => $pricing['base_price_rupiah_for_luar_negeri'],
            'base_price_rupiah_for_jakarta' => $pricing['base_price_rupiah_for_jakarta'],
            'margin_percentage' => (float) $validated['margin_percentage'],
        ]);

        return redirect()->route('product.index')->with('success', 'Product created successfully.');

    }

    public function importProduct(Request $request)
    {
        Excel::import(new ProductsImport, $request->file('import_file'));

        return back()->with('success', 'Products imported successfully.');
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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'distributor_origin' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'shipping_fee_by_air' => 'nullable|numeric|min:0',
            'dollar_base_price' => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product moved to trash successfully.');
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(string $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('product.trashed')->with('success', 'Product restored successfully.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('product.trashed')->with('success', 'Product permanently deleted.');
    }
}
