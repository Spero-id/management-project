<?php

namespace App\Http\Controllers;

use App\Exports\WbsItemExport;
use App\Imports\WbsItemImport;
use App\Models\Project;
use App\Models\ProjectWBSItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProjectWbsItemController extends Controller
{
    /**
     * Store a newly created WBS item (category or task).
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'item_type' => 'required|in:category,task',
            'parent_id' => 'nullable|integer|exists:project_wbs_items,id',
            'note' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'from' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
        ]);

        $item = ProjectWBSItem::create([
            'project_id' => $project->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'title' => $validated['title'],
            'item_type' => $validated['item_type'],
            'type' => $validated['type'] ?? null,
            'from' => $validated['from'] ?? null,
            'to' => $validated['to'] ?? null,
            'is_done' => false,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Item added successfully.');
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, ProjectWBSItem $wbsItem)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'item_type' => 'required|in:category,task',
            'parent_id' => 'nullable|integer|exists:project_wbs_items,id',
            'note' => 'nullable|string',
            'is_done' => 'nullable|boolean',
            'type' => 'nullable|string|max:255',
            'from' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
        ]);

        $wbsItem->update([
            'title' => $validated['title'],
            'item_type' => $validated['item_type'],
            'parent_id' => $validated['parent_id'] ?? null,
            'note' => $validated['note'] ?? null,
            'is_done' => $validated['is_done'] ?? $wbsItem->is_done,
            'type' => $validated['type'] ?? $wbsItem->type,
            'from' => $validated['from'] ?? $wbsItem->from,
            'to' => $validated['to'] ?? $wbsItem->to,
        ]);


        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    /**
     * Toggle is_done via API (AJAX)
     */
    public function toggle(Request $request, ProjectWBSItem $wbsItem)
    {
        $validated = $request->validate([
            'is_done' => 'required|boolean',
        ]);

        $wbsItem->is_done = $validated['is_done'];
        $wbsItem->save();

        return response()->json([
            'success' => true,
            'is_done' => (bool) $wbsItem->is_done,
            'id' => $wbsItem->id,
        ]);
    }

    /**
     * Remove the specified item.
     */
    public function destroy(ProjectWBSItem $wbsItem)
    {
        $projectId = $wbsItem->project_id;
        $wbsItem->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    /**
     * Import WBS items from Excel file.
     */
    public function import(Request $request, Project $project)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new WbsItemImport($project->id), $validated['file']);

            return redirect()->back()->with('success', 'WBS items imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to import WBS items: ' . $e->getMessage());
        }
    }

    /**
     * Export WBS items to Excel file.
     */
    public function export(Project $project)
    {
        try {
            $filename = 'wbs-items-' . $project->name . '-' . now()->format('Y-m-d') . '.xlsx';
            
            return Excel::download(new WbsItemExport($project->id), $filename);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to export WBS items: ' . $e->getMessage());
        }
    }
}
