<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * DataTable API for user listing.
     */
    public function datatableUsers(Request $request)
    {
        $query = User::with('division')
            ->select('users.*')
            ->orderByDesc('users.created_at');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return '<a href="' . route('user.show', $user->id) . '" class="text-reset">' . e($user->name) . '</a>';
            })
            ->addColumn('join_date', function ($user) {
                return $user->join_month . '-' . $user->join_year;
            })
            ->addColumn('division_code', function ($user) {
                return $user->division->kode ?? '';
            })
            ->addColumn('division_name', function ($user) {
                return $user->division->name ?? '';
            })
            ->addColumn('actions', function ($user) {
                $viewBtn = '<a href="'.route('user.show', $user->id).'" class="btn btn-icon view-btn" aria-label="View" title="View user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7" />
                    </svg>
                </a>';

                $editBtn = '<a href="'.route('user.edit', $user->id).'" class="btn btn-icon edit-btn" aria-label="Edit" title="Edit user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                </a>';

                $editBtn = '<a href="'.route('user.edit', $user->id).'" class="btn btn-icon edit-btn" aria-label="Edit" title="Edit user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                </a>';

                $deleteBtn = '<button type="button" class="btn btn-icon delete-user-btn" data-user-id="'.$user->id.'" data-user-name="'.e($user->name).'" aria-label="Delete user" title="Delete user" data-bs-toggle="modal" data-bs-target="#modal-delete-user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>';

                return $viewBtn.' '.$editBtn.' '.$deleteBtn;
            })
            ->filterColumn('division_code', function ($query, $keyword) {
                $query->whereHas('division', function ($q) use ($keyword) {
                    $q->where('kode', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('division_name', function ($query, $keyword) {
                $query->whereHas('division', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('division_code', function ($query, $order) {
                $query->join('divisions', 'users.division_id', '=', 'divisions.id')
                      ->orderBy('divisions.kode', $order);
            })
            ->orderColumn('division_name', function ($query, $order) {
                $query->join('divisions', 'users.division_id', '=', 'divisions.id')
                      ->orderBy('divisions.name', $order);
            })
            ->orderColumn('join_date', function ($query, $order) {
                $query->orderBy('users.join_year', $order)
                      ->orderBy('users.join_month', $order);
            })
            ->rawColumns(['name', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();
        $roles = Role::all();

        return view('user.create', compact('divisions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'join_month' => 'required|string',
            'join_year' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $noKaryawan = $this->generateNoKaryawan($request->type);
        $noQuotation = $this->generateNoQuotation($request->division_id);

        $division = Division::findOrFail($request->division_id);
        $uniqueId = 'SIS'.'-'.$noKaryawan.'-'.$request->join_year.'-'.$division->kode;

        // Handle file uploads
        $fotoPath = null;
        $ktpPath = null;
        $ijazahPath = null;
        $ttdImgPath = null;
        $sertifikatPaths = [];

        if ($request->hasFile('foto')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('foto'), 'documents/foto');
            $fotoPath = $request->file('foto')->storeAs('documents/foto', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ktp')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('ktp'), 'documents/ktp');
            $ktpPath = $request->file('ktp')->storeAs('documents/ktp', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ijazah')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('ijazah'), 'documents/ijazah');
            $ijazahPath = $request->file('ijazah')->storeAs('documents/ijazah', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ttd_img')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('ttd_img'), 'documents/ttd');
            $ttdImgPath = $request->file('ttd_img')->storeAs('documents/ttd', $uniqueFilename, 'public');
        }

        if ($request->hasFile('sertifikat')) {
            foreach ($request->file('sertifikat') as $file) {
                $uniqueFilename = $this->generateUniqueFilename($file, 'documents/sertifikat');
                $sertifikatPaths[] = $file->storeAs('documents/sertifikat', $uniqueFilename, 'public');
            }
        }

        $user = User::create([
            'unique_id' => $uniqueId,
            'no_karyawan' => 'SIS-'.$noKaryawan,
            'no_quotation' => $noQuotation,
            'type' => $request->type,
            'name' => $request->name,
            'email' => $request->email,
            'join_month' => $request->join_month,
            'join_year' => $request->join_year,
            'division_id' => $request->division_id,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
            'ktp' => $ktpPath,
            'ijazah' => $ijazahPath,
            'ttd_img' => $ttdImgPath,
            'sertifikat' => $sertifikatPaths,
        ]);

        // Assign role to user
        $user->assignRole($request->role);

        return redirect()->route('user.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('division')->findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $divisions = Division::all();
        $roles = Role::all();

        return view('user.edit', compact('user', 'divisions', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::findOrFail($id);

        $rules = [
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'join_month' => 'required|string',
            'join_year' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required|exists:roles,name',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ttd_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Handle file uploads
        $fotoPath = $user->foto; // Keep existing if no new file
        $ktpPath = $user->ktp; // Keep existing if no new file
        $ijazahPath = $user->ijazah; // Keep existing if no new file
        $ttdImgPath = $user->ttd_img; // Keep existing if no new file
        $sertifikatPaths = $user->sertifikat ?? []; // Keep existing if no new files

        if ($request->hasFile('foto')) {
            // Delete old file if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $uniqueFilename = $this->generateUniqueFilename($request->file('foto'), 'documents/foto');
            $fotoPath = $request->file('foto')->storeAs('documents/foto', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ktp')) {
            // Delete old file if exists
            if ($user->ktp && Storage::disk('public')->exists($user->ktp)) {
                Storage::disk('public')->delete($user->ktp);
            }
            $uniqueFilename = $this->generateUniqueFilename($request->file('ktp'), 'documents/ktp');
            $ktpPath = $request->file('ktp')->storeAs('documents/ktp', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ijazah')) {
            // Delete old file if exists
            if ($user->ijazah && Storage::disk('public')->exists($user->ijazah)) {
                Storage::disk('public')->delete($user->ijazah);
            }
            $uniqueFilename = $this->generateUniqueFilename($request->file('ijazah'), 'documents/ijazah');
            $ijazahPath = $request->file('ijazah')->storeAs('documents/ijazah', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ttd_img')) {
            // Delete old file if exists
            if ($user->ttd_img && Storage::disk('public')->exists($user->ttd_img)) {
                Storage::disk('public')->delete($user->ttd_img);
            }
            $uniqueFilename = $this->generateUniqueFilename($request->file('ttd_img'), 'documents/ttd');
            $ttdImgPath = $request->file('ttd_img')->storeAs('documents/ttd', $uniqueFilename, 'public');
        }

        if ($request->hasFile('sertifikat')) {
            // Delete old files if exist
            if ($user->sertifikat) {
                foreach ($user->sertifikat as $oldPath) {
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }

            $sertifikatPaths = [];
            foreach ($request->file('sertifikat') as $file) {
                $uniqueFilename = $this->generateUniqueFilename($file, 'documents/sertifikat');
                $sertifikatPaths[] = $file->storeAs('documents/sertifikat', $uniqueFilename, 'public');
            }
        }

        // Generate new no_karyawan and unique_id if type or division changed
        $needsNewNumber = $user->type !== $request->type || $user->division_id != $request->division_id;

        if ($needsNewNumber) {
            $noKaryawan = $this->generateNoKaryawan($request->type);
            $noQuotation = $this->generateNoQuotation($request->division_id);
            $division = Division::findOrFail($request->division_id);
            $uniqueId = 'SIS'.'-'.$noKaryawan.'-'.$request->join_year.'-'.$division->kode;
        } else {
            $noKaryawan = str_replace('SIS-', '', $user->no_karyawan);
            $noQuotation = $user->no_quotation; // Keep existing quotation number
            $division = Division::findOrFail($request->division_id);
            $uniqueId = 'SIS'.'-'.$noKaryawan.'-'.$request->join_year.'-'.$division->kode;
        }

        $updateData = [
            'unique_id' => $uniqueId,
            'no_karyawan' => 'SIS-'.$noKaryawan,
            'no_quotation' => $noQuotation,
            'type' => $request->type,
            'name' => $request->name,
            'email' => $request->email,
            'join_month' => $request->join_month,
            'join_year' => $request->join_year,
            'division_id' => $request->division_id,
            'foto' => $fotoPath,
            'ktp' => $ktpPath,
            'ijazah' => $ijazahPath,
            'ttd_img' => $ttdImgPath,
            'sertifikat' => $sertifikatPaths,
        ];

        // Only update password if it's provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Sync role - remove all current roles and assign the new one
        $user->syncRoles([$request->role]);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // Store user name for success message
            $userName = $user->name;

            // Delete associated files
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            if ($user->ktp && Storage::disk('public')->exists($user->ktp)) {
                Storage::disk('public')->delete($user->ktp);
            }

            if ($user->ijazah && Storage::disk('public')->exists($user->ijazah)) {
                Storage::disk('public')->delete($user->ijazah);
            }

            if ($user->ttd_img && Storage::disk('public')->exists($user->ttd_img)) {
                Storage::disk('public')->delete($user->ttd_img);
            }

            if ($user->sertifikat) {
                foreach ($user->sertifikat as $filePath) {
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }

            // Delete the user
            $user->delete();

            return redirect()->route('user.index')->with('success', "User '{$userName}' berhasil dihapus!");

        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }

    /**
     * Upload profile photo for authenticated user.
     */
    public function uploadProfilePhoto(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $uniqueFilename = $this->generateUniqueFilename($request->file('profile_photo'), 'documents/foto');
            $fotoPath = $request->file('profile_photo')->storeAs('documents/foto', $uniqueFilename, 'public');

            // Update user profile photo
            $user->update([
                'foto' => $fotoPath,
            ]);

            return redirect()->route('profile.index')->with('success', 'Profile photo updated successfully!');
        }

        return redirect()->route('profile.index')->with('error', 'Failed to upload profile photo.');
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

            $uniqueFilename = $this->generateUniqueFilename($request->file('signature'), 'documents/ttd');
            $ttdPath = $request->file('signature')->storeAs('documents/ttd', $uniqueFilename, 'public');

            // Update user signature
            $user->update([
                'ttd_img' => $ttdPath,
            ]);

            return redirect()->route('profile.index')->with('success', 'Signature updated successfully!');
        }

        return redirect()->route('profile.index')->with('error', 'Failed to upload signature.');
    }

    /**
     * Upload additional document to user's sertifikat array.
     */
    public function uploadDocument(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $user = User::findOrFail($id);

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

            return redirect()->route('user.show', $id)->with('success', 'Document uploaded successfully!');
        }

        return redirect()->route('user.show', $id)->with('error', 'Failed to upload document.');
    }

    /**
     * Generate nomor karyawan berdasarkan divisi
     */
    private function generateNoKaryawan($divisionName)
    {
        // Tentukan range berdasarkan nama divisi
        $ranges = [
            'BOD' => ['min' => 1, 'max' => 10],
            'MANAGER' => ['min' => 11, 'max' => 30],
            'KARYAWAN' => ['min' => 31, 'max' => 100],
            'KARYAWAN_KONTRAK' => ['min' => 101, 'max' => 200],
        ];

        // Cek apakah divisi ada dalam range yang ditentukan
        if (isset($ranges[$divisionName])) {
            $min = $ranges[$divisionName]['min'];
            $max = $ranges[$divisionName]['max'];

            // Cari nomor yang tersedia dalam range
            for ($i = $min; $i <= $max; $i++) {
                $formattedNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
                if (! User::where('no_karyawan', 'LIKE', '%'.$formattedNumber)->exists()) {
                    return $formattedNumber;
                }
            }
        }

        // Jika range penuh atau divisi tidak dikenal, cari nomor dari 201 ke atas
        $lastNumber = User::max('no_karyawan');

        // Extract numeric part from no_karyawan if it exists
        if ($lastNumber) {
            // Extract numbers from string like "SIS-0001" -> 1
            preg_match('/(\d+)/', $lastNumber, $matches);
            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 200;
        } else {
            $lastNumber = 200;
        }

        // Pastikan mulai dari 201 jika masih di bawah itu
        $startFrom = max($lastNumber + 1, 201);

        // Cari nomor yang tersedia mulai dari startFrom
        while (true) {
            $formattedNumber = str_pad($startFrom, 4, '0', STR_PAD_LEFT);
            if (! User::where('no_karyawan', 'LIKE', '%'.$formattedNumber)->exists()) {
                return $formattedNumber;
            }
            $startFrom++;
        }
    }

    /**
     * Generate nomor quotation auto increment untuk BOD dan Sales division saja
     */
    private function generateNoQuotation($divisionId)
    {
        $division = Division::findOrFail($divisionId);
        $isGenerateSalesQuotation = $division->is_generate_sales_quotation_number;

        if (! $isGenerateSalesQuotation) {
            return null;
        }

        $lastQuotationUser = User::whereNotNull('no_quotation')
            ->orderBy('no_quotation', 'desc')
            ->first();

        if ($lastQuotationUser && $lastQuotationUser->no_quotation) {
            preg_match('/(\d+)$/', $lastQuotationUser->no_quotation, $matches);
            $lastNumber = isset($matches[1]) ? (int) $matches[1] : 0;
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $nextNumber;

    }

    /**
     * Generate unique filename untuk mencegah duplicate tanpa hashing nama file
     */
    private function generateUniqueFilename($file, $directory)
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

    /**
     * Export users to Excel
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new UserExport(), 'users-' . date('Y-m-d') . '.xlsx');
    }
}
