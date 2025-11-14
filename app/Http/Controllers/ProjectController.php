<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectClientPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $projects = Project::with(['status', 'manager', 'creator'])->get();

        return view('project.index', compact('projects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on-hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'budget' => 'nullable|numeric|min:0',
            'spent' => 'nullable|numeric|min:0',
            'client_name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();

        $project = Project::create($validated);

        return redirect()->route('project.show', $project)->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {

        // Load simple WBS items for this project (categories and tasks)
        $wbsItems = \App\Models\ProjectWBSItem::where('project_id', $project->id)
            ->orderBy('id')
            ->get();

        // Load categories (WBS items with item_type = 'category' and no parent)
        $categories = \App\Models\ProjectWBSItem::where('project_id', $project->id)
            ->where('item_type', 'category')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get();

        return view('project.show', compact('project', 'wbsItems', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,on-hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'budget' => 'nullable|numeric|min:0',
            'spent' => 'nullable|numeric|min:0',
            'client_name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project->update($validated);

        return redirect()->route('project.show', $project)->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('project.index')->with('success', 'Project deleted successfully!');
    }

    public function changeStatus(Request $request, Project $project)
    {

        try {
            $status = match ($request->input('status')) {
                'on-going' => $this->changeStatusToOnGoing($request, $project),
                
                default => null,
            };

        } catch (\Exception $e) {
            dd($e->getMessage());
            // return redirect()->route('project.show', $project)->with('error', 'Failed to change project status: '.$e->getMessage());
        }

        return redirect()->route('project.show', $project)->with('success', 'Project status changed successfully.');
    }

    private function changeStatusToOnGoing(Request $request, Project $project)
    {

        $validated = $request->validate([
            'pic_project' => 'nullable|string|max:255',
            'waktu_pelaksanaan_days' => 'nullable|string|min:1',
            'client_persons' => 'nullable',
            'client_persons.*.name' => 'required_with:client_persons|string|max:255',
            'client_persons.*.phone' => 'nullable|string|max:50',
            'client_persons.*.email' => 'nullable|email|max:255',
            'client_persons.*.notes' => 'nullable|string',
        ]);

        $project->pic_project = $validated['pic_project'] ?? null;
        $project->execution_time = $validated['waktu_pelaksanaan_days'] ?? null;
        $project->status = 'on-going';

        $project->save();

        $clientPersons = json_decode($validated['client_persons'] ?? []);
        if (isset($validated['client_persons'])) {
            $project->clientPersons()->delete(); // untuk memastikan data lama dihapus

            foreach ($clientPersons as $person) {
                ProjectClientPerson::create([
                    'project_id' => $project->id,
                    'name' => $person->name ?? null,
                    'email' => $person->email ?? null,
                    'phone' => $person->phone ?? null,
                    'note' => $person->notes ?? null,
                ]);
            }
        }

        return 'ok';

    }

    /**
     * Upload a project file and attach it to the given project's file key.
     */
    public function uploadFile(Request $request)
    {
        $allowedKeys = [
            'drawing_file',
            'wbs_file',
            'project_schedule_file',
            'purchase_schedule_file',
            'pengajuan_material_project_file',
            'pengajuan_tools_project_file',
            'document',
            'po_file',
            'spk_file',
        ];

        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'file_key' => ['required', 'string', Rule::in($allowedKeys)],
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $fileKey = $validated['file_key'];

        if ($request->hasFile('file')) {
            $disk = 'public';
            // store under projects/files/{fileKey}
            $path = $request->file('file')->store("projects/files/{$fileKey}", $disk);

            // delete previous file if exists
            if (!empty($project->{$fileKey})) {
                try {
                    Storage::disk($disk)->delete($project->{$fileKey});
                } catch (\Exception $e) {
                    // non-fatal: continue
                }
            }

            $project->{$fileKey} = $path;
            $project->save();
        }

        return redirect()->route('project.show', $project)->with('success', 'File uploaded successfully.');
    }

    /**
     * Delete a stored file for a project and clear the attribute.
     */
    public function deleteFile(Project $project, $fileKey)
    {
        $allowedKeys = [
            'drawing_file',
            'wbs_file',
            'project_schedule_file',
            'purchase_schedule_file',
            'pengajuan_material_project_file',
            'pengajuan_tools_project_file',
            'document',
            'po_file',
            'spk_file',
        ];

        if (!in_array($fileKey, $allowedKeys)) {
            return redirect()->route('project.show', $project)->with('error', 'Invalid file key.');
        }

        if (!empty($project->{$fileKey})) {
            try {
                Storage::disk('public')->delete($project->{$fileKey});
            } catch (\Exception $e) {
                // ignore
            }

            $project->{$fileKey} = null;
            $project->save();
        }

        return redirect()->route('project.show', $project)->with('success', 'File deleted successfully.');
    }
}
