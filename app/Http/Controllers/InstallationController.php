<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use DB;
use Illuminate\Http\Request;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Quotation $quotation)
    {
        return view('quotation.installation.create', compact('quotation'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'quotation_id' => 'required|exists:quotations,id',
            'installations' => 'required|array|min:1',
            'installations.*.installation_id' => 'required|exists:installations,id',
            'installations.*.quantity' => 'required|integer|min:1',
            'installations.*.unit_price' => 'required|min:0',
        ];

        $request->validate($validationRules);

        try {
            DB::transaction(function () use ($request) {
                $quotation = Quotation::findOrFail($request->quotation_id);

                $quotation->installationItems()->delete();

                // Create installation items from provided data
                foreach ($request->installations as $installationData) {
                    $quotation->installationItems()->create([
                        'installation_id' => $installationData['installation_id'],
                        'quantity' => $installationData['quantity'],
                        'unit_price' => (int) str_replace(['.', ','], ['', '.'], $installationData['unit_price']),
                    ]);
                }

                // Save installation_percentage if provided (coming from the edit form)
                if ($request->has('installation_percentage')) {
                    $quotation->installation_percentage = $request->installation_percentage;
                    $quotation->save();
                }

                // Recalculate total
                $quotation->calculateTotal();
            });

        } catch (\Exception $e) {
            dd($e->getMessage());

            return redirect()->back()->withErrors(['error' => 'An error occurred while saving installation items: '.$e->getMessage()])->withInput();
        }


        return redirect()->route('quotation.show', $request->quotation_id)
            ->with('success', 'Installation items added successfully.');
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
