<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Prospect;
use App\Models\ProspectLog;
use App\Models\ProspectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProspectLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'prospect_id' => 'required|exists:prospects,id',
            'status_id' => 'required|exists:prospect_status,id',
            'note' => 'nullable|string',
            'po_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'spk_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            $prospect = Prospect::findOrFail($request->prospect_id);
            $newStatus = ProspectStatus::findOrFail($request->status_id);

            if ($newStatus->persentage == 100) {
                $request->validate([
                    'po_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                    'spk_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                ], [
                    'po_file.required' => 'File PO wajib diupload untuk status dengan progres 100%',
                    'spk_file.required' => 'File SPK wajib diupload untuk status dengan progres 100%',
                ]);
            }

            $updateData = ['status_id' => $request->status_id];

            if ($request->hasFile('po_file')) {
                $updateData['po_file'] = $this->handleFileUpload(
                    $request->file('po_file'),
                    'prospects/po',
                    'po'
                );
            }

            if ($request->hasFile('spk_file')) {
                $updateData['spk_file'] = $this->handleFileUpload(
                    $request->file('spk_file'),
                    'prospects/spk',
                    'spk'
                );
            }

            ProspectLog::create([
                'prospect_id' => $prospect->id,
                'from_status' => $prospect->status_id,
                'to_status' => $request->status_id,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);

            $prospect->update($updateData);

            if ($newStatus->persentage == 100) {
                $this->convertProspectToProject($prospect, false);
            }

            return redirect()->route('prospect.show', $prospect->id)
                ->with('success', 'Prospect log added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan log prospect: '.$e->getMessage()]);
        }
    }

    /**
     * Convert a Prospect into a Project and create a log entry.
     */
    private function convertProspectToProject(Prospect $prospect, bool $deleteProspect = false)
    {

        // Create the project from prospect data
        $project = Project::create([
            'prospect_id' => $prospect->id,
            'client_name' => $prospect->customer_name,
            'client_email' => $prospect->email,
            'client_phone' => $prospect->no_handphone,
            'company' => $prospect->company,
            'company_identity' => $prospect->company_identity,
            'target_from_month' => $prospect->target_from_month,
            'target_to_month' => $prospect->target_to_month,
            'target_from_year' => $prospect->target_from_year,
            'target_to_year' => $prospect->target_to_year,
            'status_id' => $prospect->status_id,
            'description' => $prospect->note,
            'created_by' => $prospect->created_by,
            'po_file' => $prospect->po_file,
            'spk_file' => $prospect->spk_file,
        ]);

        ProspectLog::create([
            'prospect_id' => $prospect->id,
            'from_status' => $prospect->status_id,
            'to_status' => $prospect->status_id,
            'note' => "Prospect converted to project Deal: {$prospect->customer_name} (Project ID: {$project->id})",
            'created_by' => Auth::check() ? Auth::id() : $prospect->created_by,
        ]);

        // Optionally delete the prospect
        if ($deleteProspect) {
            $prospect->delete();
        }

        return $project;
    }

    /**
     * Handle file upload and return the storage path
     */
    private function handleFileUpload($file, $directory, $prefix)
    {
        $fileName = time().'_'.$prefix.'_'.$file->getClientOriginalName();

        return $file->storeAs($directory, $fileName, 'public');
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
