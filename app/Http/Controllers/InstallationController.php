<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationAccommodationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'need_accommodation' => 'nullable',

        ];

        $request->validate($validationRules);

        try {
            DB::transaction(function () use ($request) {
                $quotation = Quotation::findOrFail($request->quotation_id);
                $quotation->need_accommodation = $request->boolean('need_accommodation');
                if ($request->boolean('need_accommodation')) {
                    $quotation->accommodation_wilayah = $request->accommodation_wilayah;
                    $quotation->accommodation_hotel_rooms = $request->accommodation_hotel_rooms;
                    $quotation->accommodation_people = $request->accommodation_people;
                    $quotation->accommodation_target_days = $request->accommodation_target_days;
                    $quotation->accommodation_plane_ticket_price = $request->accommodation_plane_ticket_price;
                    $quotation->accommodation_total_amount = $this->calculateAccommodationTotal($request);
                } else {
                    $quotation->accommodation_wilayah = null;
                    $quotation->accommodation_hotel_rooms = null;
                    $quotation->accommodation_people = null;
                    $quotation->accommodation_target_days = null;
                    $quotation->accommodation_plane_ticket_price = null;
                    $quotation->accommodation_total_amount = null;
                }
                $quotation->save();
                $quotation->installationItems()->delete();
                foreach ($request->installations as $installationData) {
                    $quotation->installationItems()->create([
                        'installation_id' => $installationData['installation_id'],
                        'quantity' => $installationData['quantity'],
                        'unit_price' => (int) str_replace(['.', ','], ['', '.'], $installationData['unit_price']),
                    ]);
                }

                if ($request->has('installation_percentage')) {
                    $quotation->installation_percentage = $request->installation_percentage;
                    $quotation->save();
                }

                if ($request->boolean('need_accommodation')) {
                    $quotation->accommodationItems()->delete();
                    $this->createAccommodationItems($quotation->id, $request);
                } else {
                    $quotation->accommodationItems()->delete();
                }

                // Recalculate total
                $quotation->calculateTotal();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving installation items: '.$e->getMessage()])->withInput();
        }

        return redirect()->route('quotation.show', $request->quotation_id)
            ->with('success', 'Installation items saved successfully.');
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

        $validationRules = [
            'quotation_id' => 'required|exists:quotations,id',
            'installations' => 'required|array|min:1',
            'installations.*.installation_id' => 'required|exists:installations,id',
            'installations.*.quantity' => 'required|integer|min:1',
            'installations.*.unit_price' => 'required|min:0',
            'need_accommodation' => 'nullable',

        ];

        $request->validate($validationRules);

        try {
            DB::transaction(function () use ($request) {
                $quotation = Quotation::findOrFail($request->quotation_id);
                $quotation->need_accommodation = $request->boolean('need_accommodation');
                if ($request->boolean('need_accommodation')) {
                    $quotation->accommodation_wilayah = $request->accommodation_wilayah;
                    $quotation->accommodation_hotel_rooms = $request->accommodation_hotel_rooms;
                    $quotation->accommodation_people = $request->accommodation_people;
                    $quotation->accommodation_target_days = $request->accommodation_target_days;
                    $quotation->accommodation_plane_ticket_price = $request->accommodation_plane_ticket_price;
                    $quotation->accommodation_total_amount = $this->calculateAccommodationTotal($request);
                } else {
                    $quotation->accommodation_wilayah = null;
                    $quotation->accommodation_hotel_rooms = null;
                    $quotation->accommodation_people = null;
                    $quotation->accommodation_target_days = null;
                    $quotation->accommodation_plane_ticket_price = null;
                    $quotation->accommodation_total_amount = null;
                }
                $quotation->save();
                $quotation->installationItems()->delete();
                foreach ($request->installations as $installationData) {
                    $quotation->installationItems()->create([
                        'installation_id' => $installationData['installation_id'],
                        'quantity' => $installationData['quantity'],
                        'unit_price' => (int) str_replace(['.', ','], ['', '.'], $installationData['unit_price']),
                    ]);
                }

                if ($request->has('installation_percentage')) {
                    $quotation->installation_percentage = $request->installation_percentage;
                    $quotation->save();
                }

                if ($request->boolean('need_accommodation')) {
                    $quotation->accommodationItems()->delete();
                    $this->createAccommodationItems($quotation->id, $request);
                } else {
                    $quotation->accommodationItems()->delete();
                }

                // Recalculate total
                $quotation->calculateTotal();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating installation items: '.$e->getMessage()])->withInput();
        }

        return redirect()->route('quotation.show', $request->quotation_id)
            ->with('success', 'Installation items updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function calculateAccommodationTotal(Request $request): int
    {
        return (int) str_replace(['.', ','], ['', '.'], $request->total_hotel_price) +
               (int) str_replace(['.', ','], ['', '.'], $request->total_flight_price) +
               (int) str_replace(['.', ','], ['', '.'], $request->total_transportation_price);
    }

    private function createAccommodationItems(int $quotationId, Request $request): void
    {
        $items = [
            ['name' => 'Total Harga Hotel', 'unit_price' => $request->total_hotel_price],
            ['name' => 'Harga Pesawat', 'unit_price' => $request->total_flight_price],
            ['name' => 'Harga Transportasi Kendaraan', 'unit_price' => $request->total_transportation_price],
        ];

        foreach ($items as $item) {
            QuotationAccommodationItem::create([
                'quotation_id' => $quotationId,
                'name' => $item['name'],
                'unit_price' => (int) str_replace(['.', ','], ['', '.'], $item['unit_price']),
            ]);
        }
    }
}
