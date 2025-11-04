<?php

namespace App\Http\Controllers;

use App\Models\ProspectStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProspectStatusController extends Controller
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
            'persentage' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:7',
        ]);

        ProspectStatus::create([
            'name' => $request->name,
            'persentage' => $request->persentage,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Prospect status created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProspectStatus $prospectStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProspectStatus $prospectStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProspectStatus $prospectStatus)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'persentage' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:7',
        ]);

        $prospectStatus->update([
            'name' => $request->name,
            'persentage' => $request->persentage,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Prospect status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProspectStatus $prospectStatus)
    {
        $prospectStatus->delete();
        return redirect()->back()->with('success', 'Prospect status deleted successfully.');
    }

    /**
     * DataTable API for prospect statuses
     */
    public function datatable()
    {
        $statuses = ProspectStatus::select(['id', 'name', 'persentage', 'color']);

        return DataTables::of($statuses)
            ->make(true);
    }
}