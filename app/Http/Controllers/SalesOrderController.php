<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $prospect = Prospect::find($id);
        if ($prospect->minuteOfMeetings == null) {
            $minuteOfMeeting = $prospect->minuteOfMeetings()->create([
                'body' => '',
                'created_by' => Auth::id(),
            ]);
        }

        $projectUsers = User::role('PROJECT')->get(['id', 'name']);

        return view('sales-order.create', compact('prospect', 'projectUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'pic_project' => 'required|string|max:255',
            'deadline_days' => 'required|integer|min:1',

            // Contact Information (at least one contact required)
            'contacts' => 'required|array|min:1',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.phone' => 'required|string|max:20',
            'contacts.*.email' => 'required|email|max:255',
            'contacts.*.position' => 'nullable|string|max:255',

            // File Uploads (optional)
            'rfp_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:5120',
            'proposal_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:5120',
        ]);

        try {

            $prospect = Prospect::findOrFail($request->input('prospect_id'));
            $prospect->update(['is_converted_to_project' => true]);

            $project = Project::create([
                'prospect_id' => $prospect->id,
                'client_name' => $prospect->customer_name,
                'client_email' => $prospect->email,
                'client_phone' => $prospect->no_handphone,
                'company' => $prospect->company,
                'company_identity' => $prospect->company_identity,
                'project_name' => $request->input('project_name'),
                // 'target_from_month' => $prospect->target_from_month,
                // 'target_to_month' => $prospect->target_to_month,
                // 'target_from_year' => $prospect->target_from_year,
                // 'target_to_year' => $prospect->target_to_year,
                // 'status_id' => $prospect->status_id,
                'description' => '',
                'status' => 'on-going',
                'percentage' => 0,
                'created_by' => Auth::id(),
                'po_file' => $prospect->po_file,
                'spk_file' => $prospect->spk_file,
                'drawing_file' => null,
                'wbs_file' => null,
                'project_schedule_file' => null,
                'purchase_schedule_file' => null,
                'pengajuan_material_project_file' => null,
                'pengajuan_tools_project_file' => null,
                'pic_project' => $request->input('pic_project'),
                'execution_time' => $request->input('deadline_days'),
            ]);

            $minuteOfMeeting = $prospect->minuteOfMeetings;
            if ($minuteOfMeeting) {
                $minuteOfMeeting->noteable_type = Project::class;
                $minuteOfMeeting->noteable_id = $project->id;
                $minuteOfMeeting->save();
            }

            // tambahkan project client contacts
            foreach ($request->input('contacts') as $contactData) {
                $project->clientPersons()->create([
                    'name' => $contactData['name'],
                    'phone' => $contactData['phone'],
                    'email' => $contactData['email'],
                    'position' => $contactData['position'] ?? null,
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while processing your request: '.$e->getMessage()])->withInput();
        }

        // Validation passed - return success response
        return redirect()->to(route('prospect.index'))->with('success', 'Sales order data validated successfully!');
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

    /**
     * Save minute of meeting via AJAX.
     */
    public function saveMinuteOfMeeting(Request $request): JsonResponse
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'body' => 'required|string',
        ]);

        try {
            $prospect = Prospect::findOrFail($request->input('prospect_id'));

            $minuteOfMeeting = $prospect->minuteOfMeetings()->updateOrCreate(
                ['noteable_type' => Prospect::class, 'noteable_id' => $prospect->id],
                [
                    'body' => $request->input('body'),
                    'created_by' => Auth::id(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Minute of meeting saved successfully',
                'data' => $minuteOfMeeting,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving minute of meeting: '.$e->getMessage(),
            ], 500);
        }
    }
}
