<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectWBSItem;
use Illuminate\Http\Request;

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
        ]);

        $item = ProjectWBSItem::create([
            'project_id' => $project->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'title' => $validated['title'],
            'item_type' => $validated['item_type'],
            'is_done' => false,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('project.show', $project)->with('success', 'Item added.');
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
        ]);

        $wbsItem->update([
            'title' => $validated['title'],
            'item_type' => $validated['item_type'],
            'parent_id' => $validated['parent_id'] ?? null,
            'note' => $validated['note'] ?? null,
            'is_done' => $validated['is_done'] ?? $wbsItem->is_done,
        ]);

        return redirect()->route('project.show', $wbsItem->project_id)->with('success', 'Item updated.');
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
        return redirect()->route('project.show', $projectId)->with('success', 'Item deleted.');
    }
}
