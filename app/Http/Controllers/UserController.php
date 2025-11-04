<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('division')->get();

        return view('user.index', compact('users'));
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
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sales_target' => 'nullable|string|max:255',
        ]);

        $noKaryawan = $this->generateNoKaryawan($request->type);
        $noQuotation = $this->generateNoQuotation($request->division_id);

        $division = Division::findOrFail($request->division_id);
        $uniqueId = 'SIS'.'-'.$noKaryawan.'-'.$request->join_year.'-'.$division->kode;

        // Handle file uploads
        $ktpPath = null;
        $ijazahPath = null;
        $sertifikatPaths = [];

        if ($request->hasFile('ktp')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('ktp'), 'documents/ktp');
            $ktpPath = $request->file('ktp')->storeAs('documents/ktp', $uniqueFilename, 'public');
        }

        if ($request->hasFile('ijazah')) {
            $uniqueFilename = $this->generateUniqueFilename($request->file('ijazah'), 'documents/ijazah');
            $ijazahPath = $request->file('ijazah')->storeAs('documents/ijazah', $uniqueFilename, 'public');
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
            'ktp' => $ktpPath,
            'ijazah' => $ijazahPath,
            'sertifikat' => $sertifikatPaths,
            'sales_target' => $request->target,
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
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sales_target' => 'nullable|string|max:255',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Handle file uploads
        $ktpPath = $user->ktp; // Keep existing if no new file
        $ijazahPath = $user->ijazah; // Keep existing if no new file
        $sertifikatPaths = $user->sertifikat ?? []; // Keep existing if no new files

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
            'ktp' => $ktpPath,
            'ijazah' => $ijazahPath,
            'sertifikat' => $sertifikatPaths,
            'sales_target' => $request->target,
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
            if ($user->ktp && Storage::disk('public')->exists($user->ktp)) {
                Storage::disk('public')->delete($user->ktp);
            }

            if ($user->ijazah && Storage::disk('public')->exists($user->ijazah)) {
                Storage::disk('public')->delete($user->ijazah);
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
}
