<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
}
