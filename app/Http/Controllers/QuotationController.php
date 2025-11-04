<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\QuotationAccommodationItem;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotations = Quotation::with('prospect')->latest()->paginate(10);

        return view('prospect.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Prospect $prospect)
    {

        $accommodationCategory = Accommodation::all();

        return view('quotation.create', compact(['prospect', 'accommodationCategory']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'prospect_id' => 'required|exists:prospects,id',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|min:0',
            'need_accommodation' => 'nullable|boolean',
            'accommodation_wilayah' => 'nullable|string',
            'accommodation_rooms' => 'nullable|integer|min:1',
            'accommodation_people' => 'nullable|integer|min:1',
            'accommodation_target_days' => 'nullable|integer|min:1',
            'accommodation_ticket_price' => 'nullable|integer|min:0',
        ];

        $request->validate($validationRules);

        $quotation = DB::transaction(function () use ($request) {
            $data = $this->prepareQuotationData($request);
            $quotation = Quotation::create($data);

            if ($request->boolean('need_accommodation')) {
                $this->createAccommodationItems($quotation->id, $request);
            }

            $this->createQuotationItems($quotation->id, $request->products);

            $quotation->calculateTotal();

            return $quotation;
        });

        return redirect()->route('installation.create', ['quotation' => $quotation->id]);
    }

    private function prepareQuotationData(Request $request): array
    {
        $data = [
            'created_by' => Auth::user()->id,
            'prospect_id' => $request->prospect_id,
            'notes' => $request->notes,
            'status' => 'draft',
            'need_accommodation' => $request->boolean('need_accommodation'),
        ];

        if ($request->boolean('need_accommodation')) {
            $data['accommodation_wilayah'] = $request->accommodation_wilayah;
            $data['accommodation_hotel_rooms'] = $request->accommodation_hotel_rooms;
            $data['accommodation_people'] = $request->accommodation_people;
            $data['accommodation_target_days'] = $request->accommodation_target_days;
            $data['accommodation_plane_ticket_price'] = $request->accommodation_plane_ticket_price;
            $data['accommodation_total_amount'] = $this->calculateAccommodationTotal($request);
        }

        return $data;
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

    private function createQuotationItems(int $quotationId, array $products): void
    {
        foreach ($products as $productData) {
            QuotationItem::create([
                'quotation_id' => $quotationId,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'unit_price' => (int) str_replace(['.', ','], ['', '.'], $productData['unit_price']),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotation = Quotation::with(['prospect', 'items.product', 'installationItems.installation', 'accommodationItems'])->findOrFail($id);

        return view('quotation.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quotation = Quotation::with(['items', 'installationItems'])->findOrFail($id);
        $prospect = $quotation->prospect;
        $accommodationCategory = Accommodation::all();

        return view('quotation.edit', compact('quotation', 'prospect', 'accommodationCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validationRules = [
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,accepted,rejected',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required',
            'is_revision' => 'boolean',
            'need_accommodation' => 'nullable|boolean',

            'accommodation_wilayah' => 'nullable|string',
            'accommodation_hotel_rooms' => 'nullable|integer|min:1',
            'accommodation_people' => 'nullable|integer|min:1',
            'accommodation_target_days' => 'nullable|integer|min:1',
            'accommodation_plane_ticket_price' => 'nullable',
            'total_hotel_price' => 'nullable',
            'total_flight_price' => 'nullable',
            'total_transportation_price' => 'nullable',
        ];


        $request->validate($validationRules);


        DB::transaction(function () use ($request, $quotation) {
            $updateData = [
                'notes' => $request->notes,
                'status' => $request->status,
                'need_accommodation' => $request->boolean('need_accommodation'),
            ];

            if ($request->boolean('need_accommodation')) {
                $updateData['accommodation_wilayah'] = $request->accommodation_wilayah;
                $updateData['accommodation_hotel_rooms'] = $request->accommodation_hotel_rooms;
                $updateData['accommodation_people'] = $request->accommodation_people;
                $updateData['accommodation_target_days'] = $request->accommodation_target_days;
                $updateData['accommodation_plane_ticket_price'] = $request->accommodation_plane_ticket_price;
                $updateData['accommodation_total_amount'] = $this->calculateAccommodationTotal($request);
            } else {
                $updateData['accommodation_wilayah'] = null;
                $updateData['accommodation_hotel_rooms'] = null;
                $updateData['accommodation_people'] = null;
                $updateData['accommodation_target_days'] = null;
                $updateData['accommodation_plane_ticket_price'] = null;
                $updateData['accommodation_total_amount'] = null;
            }

            $quotation->update($updateData);

            $quotation->items()->delete();

            foreach ($request->products as $productData) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => (int) str_replace(['.', ','], ['', '.'], $productData['unit_price']),
                ]);
            }

            if ($request->boolean('need_accommodation')) {
                $quotation->accommodationItems()->delete();
                $this->createAccommodationItems($quotation->id, $request);
            } else {
                $quotation->accommodationItems()->delete();
            }

            $quotation->calculateTotal();
        });

        return redirect()->route('quotation.show', $quotation->id)
            ->with('success', 'Quotation updated successfully.');
    }

    public function generatePDF(Quotation $quotation)
    {

        $quotation = Quotation::with(['prospect', 'items.product', 'installationItems.installation', 'accommodationItems'])->findOrFail($quotation->id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('quotation.pdf', compact('quotation'));

        return $pdf->download('quotation_'.$quotation->id.'.pdf');
    }

    public function destroy(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('prospect.index')
            ->with('success', 'Quotation deleted successfully.');
    }
}
