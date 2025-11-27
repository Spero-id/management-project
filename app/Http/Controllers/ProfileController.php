<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

final class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user()->load('division');

        return view('profile.index', compact('user'));
    }

    /**
     * Upload signature for authenticated user.
     */
    public function uploadSignature(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($user->ttd_img && Storage::disk('public')->exists($user->ttd_img)) {
                Storage::disk('public')->delete($user->ttd_img);
            }

            $file = $request->file('signature');
            $uniqueFilename = $this->generateUniqueFilename($file, 'documents/ttd');
            $filePath = $file->storeAs('documents/ttd', $uniqueFilename, 'public');

            $user->update([
                'ttd_img' => $filePath,
            ]);

            return redirect()->route('profile.index')->with('success', 'Signature uploaded successfully!');
        }

        return redirect()->route('profile.index')->with('error', 'Failed to upload signature.');
    }

    /**
     * Upload additional document to authenticated user's sertifikat array.
     */
    public function uploadDocument(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $uniqueFilename = $this->generateUniqueFilename($file, 'documents/sertifikat');
            $filePath = $file->storeAs('documents/sertifikat', $uniqueFilename, 'public');

            // Get existing sertifikat array or initialize empty array
            $sertifikatPaths = $user->sertifikat ?? [];

            // Add new document to array
            $sertifikatPaths[] = $filePath;

            // Update user with new sertifikat array
            $user->update([
                'sertifikat' => $sertifikatPaths,
            ]);

            return redirect()->route('profile.index')->with('success', 'Document uploaded successfully!');
        }

        return redirect()->route('profile.index')->with('error', 'Failed to upload document.');
    }

    /**
     * Generate unique filename to prevent duplicates.
     */
    private function generateUniqueFilename($file, string $directory): string
    {
        $originalName = $file->getClientOriginalName();
        $pathInfo = pathinfo($originalName);
        $filename = $pathInfo['filename'];
        $extension = isset($pathInfo['extension']) ? '.'.$pathInfo['extension'] : '';

        $uniqueFilename = $originalName;
        $counter = 1;

        // Check if file exists in storage and generate unique name if needed
        while (Storage::disk('public')->exists($directory.'/'.$uniqueFilename)) {
            $uniqueFilename = $filename.'_'.$counter.$extension;
            $counter++;
        }

        return $uniqueFilename;
    }
}
