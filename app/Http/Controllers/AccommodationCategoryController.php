<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AccommodationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting.index');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Accommodation::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Accommodation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $accommodation->update([
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Accommodation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();
        return redirect()->back()->with('success', 'Accommodation deleted successfully.');
    }

    /**
     * DataTable API for accommodations
     */
    public function datatable()
    {
        $accommodations = Accommodation::select(['id', 'name', 'price']);

        return DataTables::of($accommodations)
            ->editColumn('price', function ($accommodation) {
                return 'Rp ' . number_format($accommodation->price, 0, ',', '.');
            })
            ->make(true);
    }

    /**
     * Search accommodations for Select2
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        $accommodations = Accommodation::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'price')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $accommodations->map(function ($accommodation) {
            return [
                'id' => $accommodation->id,
                'text' => $accommodation->name,
                'price' => $accommodation->price
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $accommodations->hasMorePages()
            ]
        ]);
    }
}
