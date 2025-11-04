<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::all();
        $statuses = \App\Models\ProspectStatus::all();
        $accommodations = \App\Models\Accommodation::all();
        $installations = \App\Models\Installation::all();

        return view('setting.index', [
            'divisions' => $divisions,
            'statuses' => $statuses,
            'accommodations' => $accommodations,
            'installations' => $installations,
        ]);
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
            'kode' => 'required|string|max:10|unique:divisions,kode',
        ]);

        Division::create([
            'name' => $request->name,
            'kode' => $request->kode,
        ]);

        return redirect()->back()->with('success', 'Division created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:10|unique:divisions,kode,' . $division->id,
        ]);

        $division->update([
            'name' => $request->name,
            'kode' => $request->kode,
        ]);

        return redirect()->back()->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        $division->delete();
        return redirect()->back()->with('success', 'Division deleted successfully.');
    }

    /**
     * DataTable API for divisions
     */
    public function datatable()
    {
        $divisions = Division::select(['id', 'name', 'kode']);

        return DataTables::of($divisions)
            ->make(true);
    }
}