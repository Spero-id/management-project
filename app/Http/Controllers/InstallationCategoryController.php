<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InstallationCategoryController extends Controller
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
            'description' => 'nullable|string',
            'proportional' => 'required|numeric|min:0|max:999.99',
        ]);

        Installation::create([
            'name' => $request->name,
            'description' => $request->description,
            'proportional' => $request->proportional,
        ]);

        return redirect()->back()->with('success', 'Installation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Installation $installation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Installation $installation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Installation $installation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'proportional' => 'required|numeric|min:0|max:999.99',
        ]);

        $installation->update([
            'name' => $request->name,
            'description' => $request->description,
            'proportional' => $request->proportional,
        ]);

        return redirect()->back()->with('success', 'Installation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Installation $installation)
    {
        $installation->delete();
        return redirect()->back()->with('success', 'Installation deleted successfully.');
    }

    /**
     * DataTable API for installations
     */
    public function datatable()
    {
        $installations = Installation::select(['id', 'name', 'description', 'proportional']);

        return DataTables::of($installations)
            ->editColumn('proportional', function ($installation) {
                return $installation->proportional . '%';
            })
            ->make(true);
    }

    /**
     * Search installations for Select2
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        $installations = Installation::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'description', 'proportional')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $installations->map(function ($installation) {
            return [
                'id' => $installation->id,
                'text' => $installation->name,
                'description' => $installation->description,
                'proportional' => $installation->proportional
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $installations->hasMorePages()
            ]
        ]);
    }
}
