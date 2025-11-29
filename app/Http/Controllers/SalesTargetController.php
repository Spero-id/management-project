<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\User;
use Illuminate\Http\Request;

final class SalesTargetController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $salesMembers = User::whereHas('roles', function ($query) {
            $query->where('name', 'sales');
        })->get();

        // Get selected sales user ID from query parameter, or use first sales member
        $selectedSalesId = $request->query('sales_id') ?? $salesMembers->first()?->id;

        // Get selected sales user if exists
        $selectedSales = null;
        $salesTarget = null;

        if ($selectedSalesId) {
            $selectedSales = User::whereHas('roles', function ($query) {
                $query->where('name', 'sales');
            })->find($selectedSalesId);

            // Get sales target for selected sales member
            if ($selectedSales) {
                $salesTarget = SalesTarget::where('user_id', $selectedSalesId)
                    ->where('year', $currentYear)
                    ->first();
            }
        }

        return view('setting.sales-target', compact('salesMembers', 'selectedSales', 'salesTarget', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_gross_profit' => 'required|numeric|min:0',
            'target_monthly' => 'required|numeric|min:0',
            'target_yearly' => 'required|numeric|min:0',
            'year' => 'required|integer',
        ]);


        SalesTarget::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'year' => $validated['year'],
            ],
            [
                'target_gross_profit' => $validated['target_gross_profit'],
                'target_monthly' => $validated['target_monthly'],
                'target_yearly' => $validated['target_yearly'],
            ]
        );

        return redirect()->back()
            ->with('success', 'Sales target berhasil disimpan');
    }

    public function update(Request $request, SalesTarget $salesTarget)
    {

        $validated = $request->validate([
            'target_gross_profit' => 'required|numeric|min:0',
            'target_monthly' => 'required|numeric|min:0',
            'target_yearly' => 'required|numeric|min:0',
        ]);

        $salesTarget->update($validated);
        return redirect()->back()
            ->with('success', 'Sales target berhasil diperbarui');
    }

    
}
