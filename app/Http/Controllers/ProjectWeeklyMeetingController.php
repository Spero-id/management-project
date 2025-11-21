<?php

namespace App\Http\Controllers;

use App\Exports\WeeklyMeetingExport;
use App\Imports\WeeklyMeetingImport;
use App\Models\ProjectWeeklyMeeting;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProjectWeeklyMeetingController extends Controller
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

    public function datatable($projectId)
    {
        $statuses = ProjectWeeklyMeeting::where('project_id', $projectId)->get();

        return DataTables::of($statuses)
            ->addColumn('action', function ($row) {
                $editBtn = '<button type="button" class="btn btn-icon edit-btn" data-id="'.$row->id.'" aria-label="Edit" title="Edit weekly meeting">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                </button>';

                $deleteBtn = '<button type="button" class="btn btn-icon delete-btn" data-id="'.$row->id.'" aria-label="Delete weekly meeting">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>';

                return $editBtn.' '.$deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'task' => 'required|string|max:255',
            'petugas' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_date' => 'required|date',
            'notes' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        ProjectWeeklyMeeting::create($validated);

        return redirect()->back()->with('success', 'Weekly meeting created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meeting = ProjectWeeklyMeeting::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $meeting,
        ]);
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
        $meeting = ProjectWeeklyMeeting::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'task' => 'required|string|max:255',
            'petugas' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_date' => 'required|date',
            'notes' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $meeting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Weekly meeting updated successfully',
            'data' => $meeting,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meeting = ProjectWeeklyMeeting::findOrFail($id);
        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Weekly meeting deleted successfully',
        ]);
    }

    /**
     * Export weekly meetings to Excel
     */
    public function export(int $projectId)
    {
        return Excel::download(
            new WeeklyMeetingExport($projectId),
            'weekly-meetings-project-'.$projectId.'.xlsx'
        );
    }

    /**
     * Import weekly meetings from Excel
     */
    public function import(Request $request, int $projectId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new WeeklyMeetingImport($projectId), $request->file('file'));
            return redirect()->back()->with('success', 'Weekly meetings imported successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing weekly meetings: '.$e->getMessage());
        }
    }

    /**
     * Get list of users for dropdown
     */
    public function getUsers()
    {
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
}
