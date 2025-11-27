<?php

namespace App\Http\Controllers;

use App\Models\QuotationCondition;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $divisions = \App\Models\Division::all();
        $statuses = \App\Models\ProspectStatus::all();
        $accommodations = \App\Models\Accommodation::all();
        $installations = \App\Models\Installation::all();
        $dolarRateSetting = Setting::where('setting_name', 'currency_exchange_rate')->first()->setting_value ?? '0';
        $totalJasaSetting = Setting::where('setting_name', 'total_jasa')->first()->setting_value ?? '0';

        return view('setting.sales', compact('divisions', 'statuses', 'accommodations', 'installations', 'dolarRateSetting', 'totalJasaSetting'));
    }

    public function salesTarget()
    {


        return view('setting.sales-target');
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
     * Update the currency exchange rate.
     */
    public function updateCurrencyExchange(Request $request)
    {
        $request->validate([
            'dolar_rate' => 'required|numeric|min:0',
        ]);

        Setting::where('setting_name', 'currency_exchange_rate')->update([
            'setting_value' => $request->input('dolar_rate'),
        ]);

        // Logic to update the currency exchange rate
        // Example: Update in the database or a configuration file

        return redirect()->back()->with('success', 'Currency exchange rate updated successfully.');

    }

    /**
     * Update the total jasa setting.
     */
    public function updateTotalJasa(Request $request)
    {
        $request->validate([
            'total_jasa' => 'required|numeric|min:0',
        ]);

        Setting::where('setting_name', 'total_jasa')->update([
            'setting_value' => $request->input('total_jasa'),
        ]);

        return redirect()->back()->with('success', 'Total jasa updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get QuotationCondition datatable data.
     */
    public function quotationConditionDatatable(Request $request): JsonResponse
    {
        $columns = ['id', 'condition', 'created_at'];

        $totalData = QuotationCondition::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = QuotationCondition::query();

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where('condition', 'LIKE', "%{$search}%");
            $totalFiltered = $query->count();
        }

        $quotationConditions = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];
        foreach ($quotationConditions as $quotationCondition) {
            $data[] = [
                'id' => $quotationCondition->id,
                'condition' => $quotationCondition->condition,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created QuotationCondition.
     */
    public function storeQuotationCondition(Request $request)
    {
        $request->validate([
            'condition' => 'required|string|max:255',
        ]);

        QuotationCondition::create([
            'condition' => $request->input('condition'),
        ]);

        return redirect()->back()->with('success', 'Quotation condition created successfully.');
    }

    /**
     * Update the specified QuotationCondition.
     */
    public function updateQuotationCondition(Request $request, QuotationCondition $quotationCondition)
    {
        $request->validate([
            'condition' => 'required|string|max:255',
        ]);

        $quotationCondition->update([
            'condition' => $request->input('condition'),
        ]);

        return redirect()->back()->with('success', 'Quotation condition updated successfully.');
    }

    /**
     * Remove the specified QuotationCondition.
     */
    public function destroyQuotationCondition(QuotationCondition $quotationCondition)
    {
        $quotationCondition->delete();

        return redirect()->back()->with('success', 'Quotation condition deleted successfully.');
    }
}
