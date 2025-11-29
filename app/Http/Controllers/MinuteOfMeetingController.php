<?php

namespace App\Http\Controllers;

use App\Models\MinuteOfMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MinuteOfMeetingController extends Controller
{
    /**
     * Store a newly created minute of meeting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'noteable_type' => 'required|string',
            'noteable_id' => 'required|integer',
            'body' => 'required|string|max:65535',
        ]);

        MinuteOfMeeting::create([
            'noteable_type' => $validated['noteable_type'],
            'noteable_id' => $validated['noteable_id'],
            'body' => $validated['body'],
            'created_by' => Auth::id(),
        ]);

        // Determine redirect based on noteable_type
        $redirectRoute = match ($validated['noteable_type']) {
            'App\Models\Project' => route('project.show', $validated['noteable_id']),
            default => route('project.index'),
        };

        return redirect($redirectRoute)->with('success', 'Minutes of meeting added successfully.');
    }

    /**
     * Update the specified minute of meeting.
     */
    public function update(Request $request, MinuteOfMeeting $mom)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:65535',
        ]);

        $mom->update([
            'body' => $validated['body'],
        ]);

        // Determine redirect based on noteable_type
        $redirectRoute = match ($mom->noteable_type) {
            'App\Models\Project' => route('project.show', $mom->noteable_id),
            default => route('project.index'),
        };

        return redirect($redirectRoute)->with('success', 'Minutes of meeting updated successfully.');
    }

    /**
     * Remove the specified minute of meeting.
     */
    public function destroy(MinuteOfMeeting $mom)
    {
        $noteableType = $mom->noteable_type;
        $noteableId = $mom->noteable_id;
        
        $mom->delete();

        // Determine redirect based on noteable_type
        $redirectRoute = match ($noteableType) {
            'App\Models\Project' => route('project.show', $noteableId),
            default => route('project.index'),
        };

        return redirect($redirectRoute)->with('success', 'Minutes of meeting deleted successfully.');
    }
}