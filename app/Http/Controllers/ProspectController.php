<?php

namespace App\Http\Controllers;

use App\Imports\QuotationItemsImport;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\ProspectStatus;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProspectController extends Controller
    /**
     * Store a new prospect log for a prospect.
     */
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        if (Auth::user()->can('VIEW_ALL_PROSPECT')) {
            $prospects = Prospect::with(['quotations', 'prospectStatus'])->get();

        } else {
            $prospects = Prospect::with(['quotations', 'prospectStatus'])->where('created_by', Auth::id())->get();

        }
        $prospects->map(function ($prospect) {
            $prospect->status = ProspectStatus::find($prospect->status_id);
        });
        // $prospectStatus = $prospect->status_id;

        return view('prospect.index', compact('prospects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesUser = User::whereHas('roles', function ($query) {
            $query->where('name', 'SALES');
        })->get();

        return view('prospect.create', compact('salesUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:prospects,email',
            'company' => 'required|string|max:255',
            'company_identity' => 'required|string|max:255',
            'pre_sales' => 'required|exists:users,id',
            'target_deal_from_month' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'target_deal_to_month' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'target_deal_from_year' => 'required|integer|min:2025|max:2040',
            'target_deal_to_year' => 'required|integer|min:2025|max:2040',
            'note' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Nama customer harus diisi',
            'no_handphone.required' => 'Nomor handphone harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar sebagai prospect',
            'company.required' => 'Nama perusahaan harus diisi',
            'company_identity.required' => 'Identitas perusahaan harus diisi',
            'pre_sales.required' => 'Pre sales person harus dipilih',
            'pre_sales.exists' => 'Pre sales person tidak ditemukan',
            'target_deal_from_month.required' => 'Bulan target deal dari harus dipilih',
            'target_deal_to_month.required' => 'Bulan target deal sampai harus dipilih',
            'target_deal_from_year.required' => 'Tahun target deal dari harus dipilih',
            'target_deal_to_year.required' => 'Tahun target deal sampai harus dipilih',
        ]);

        try {
            // Validasi periode target deal
            $fromMonth = (int) $validated['target_deal_from_month'];
            $toMonth = (int) $validated['target_deal_to_month'];
            $fromYear = (int) $validated['target_deal_from_year'];
            $toYear = (int) $validated['target_deal_to_year'];

            // Create date objects for comparison
            $fromDate = \DateTime::createFromFormat('Y-m', $fromYear.'-'.sprintf('%02d', $fromMonth));
            $toDate = \DateTime::createFromFormat('Y-m', $toYear.'-'.sprintf('%02d', $toMonth));

            if ($fromDate > $toDate) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['target_deal' => 'Periode "dari" tidak boleh lebih besar dari periode "sampai"']);
            }

            // Handle file upload
            $documentPath = null;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
                $documentPath = $file->storeAs('prospects/documents', $fileName, 'public');
            }

            // Mulai database transaction
            DB::beginTransaction();

            try {
                // Simpan data ke database
                $prospect = Prospect::create([
                    'customer_name' => $validated['customer_name'],
                    'no_handphone' => $validated['no_handphone'],
                    'email' => $validated['email'],
                    'company' => $validated['company'],
                    'company_identity' => $validated['company_identity'],
                    'pre_sales' => $validated['pre_sales'],
                    'target_from_month' => $validated['target_deal_from_month'],
                    'target_to_month' => $validated['target_deal_to_month'],
                    'target_from_year' => $validated['target_deal_from_year'],
                    'target_to_year' => $validated['target_deal_to_year'],
                    'note' => $validated['note'],
                    'document' => $documentPath,
                    'status_id' => 1,
                    'created_by' => Auth::user()->id,
                ]);

                if ($request->hasFile('document')) {
                    $quotation = Quotation::create([
                        'created_by' => Auth::user()->id,
                        'prospect_id' => $prospect->id,
                        'notes' => '',
                        'status' => 'draft',
                    ]);

                    $quotationItemsData = Excel::toArray(new QuotationItemsImport, $request->file('document'));
                    foreach ($quotationItemsData[0] as $itemData) {
                        if ($itemData['product_id'] && $itemData['quantity']) {
                            $product = Product::find($itemData['product_id']);
                            if (! $product) {
                                continue;
                            }
                            QuotationItem::create([
                                'quotation_id' => $quotation->id,
                                'product_id' => $itemData['product_id'],
                                'quantity' => $itemData['quantity'],
                                'unit_price' => $product->price,
                                'subtotal' => $itemData['quantity'] * ($product ? $product->price : 0),
                            ]);
                        }
                    }

                    $quotation->calculateTotal();
                    DB::commit();

                    return redirect()->route('quotation.edit', ['quotation' => $quotation->id, 'is_revision' => false])
                        ->with('success', 'Prospect berhasil dibuat.');
                }

                DB::commit();

                return redirect()->route('quotation.create', ['prospect' => $prospect->id]);

            } catch (\Exception $dbException) {
                // Rollback transaction jika ada error
                DB::rollback();
                throw $dbException;
            }

        } catch (\Exception $e) {
            // Jika terjadi error, hapus file yang sudah diupload (jika ada)
            if (isset($documentPath) && $documentPath && Storage::disk('public')->exists($documentPath)) {
                Storage::disk('public')->delete($documentPath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan prospect: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prospect = Prospect::with(['quotations', 'logs'])->findOrFail($id);
        $prospectStatuses = ProspectStatus::all();

        return view('prospect.show', compact('prospect', 'prospectStatuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prospect = Prospect::with('prospectStatus')->findOrFail($id);

        // Prevent editing if progress is 100%
        if (($prospect->prospectStatus->persentase ?? 0) >= 100) {
            return redirect()->route('prospect.show', $id)
                ->with('error', 'Prospect dengan progress 100% tidak dapat diedit lagi.');
        }

        $salesUser = User::whereHas('roles', function ($query) {
            $query->where('name', 'SALES');
        })->get();

        return view('prospect.edit', compact('prospect', 'salesUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $prospect = Prospect::with('prospectStatus')->findOrFail($id);

        // Prevent updating if progress is 100%
        if (($prospect->prospectStatus->persentase ?? 0) >= 100) {
            return redirect()->route('prospect.show', $id)
                ->with('error', 'Prospect dengan progress 100% tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:prospects,email,'.$id,
            'company' => 'required|string|max:255',
            'company_identity' => 'required|string|max:255',
            'pre_sales' => 'required|exists:users,id',
            'target_deal_from_month' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'target_deal_to_month' => 'required|string|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'target_deal_from_year' => 'required|integer|min:2025|max:2040',
            'target_deal_to_year' => 'required|integer|min:2025|max:2040',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png|max:5120', // 5MB, optional for update
            'note' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Nama customer harus diisi',
            'no_handphone.required' => 'Nomor handphone harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar sebagai prospect lain',
            'company.required' => 'Nama perusahaan harus diisi',
            'company_identity.required' => 'Identitas perusahaan harus diisi',
            'pre_sales.required' => 'Pre sales person harus dipilih',
            'pre_sales.exists' => 'Pre sales person tidak ditemukan',
            'target_deal_from_month.required' => 'Bulan target deal dari harus dipilih',
            'target_deal_to_month.required' => 'Bulan target deal sampai harus dipilih',
            'target_deal_from_year.required' => 'Tahun target deal dari harus dipilih',
            'target_deal_to_year.required' => 'Tahun target deal sampai harus dipilih',
            'document.mimes' => 'Format dokumen harus PDF, DOC, DOCX, XLS, XLSX, CSV, JPG, atau PNG',
            'document.max' => 'Ukuran dokumen maksimal 5MB',
        ]);

        try {
            // Validasi periode target deal
            $fromMonth = (int) $validated['target_deal_from_month'];
            $toMonth = (int) $validated['target_deal_to_month'];
            $fromYear = (int) $validated['target_deal_from_year'];
            $toYear = (int) $validated['target_deal_to_year'];

            // Create date objects for comparison
            $fromDate = \DateTime::createFromFormat('Y-m', $fromYear.'-'.sprintf('%02d', $fromMonth));
            $toDate = \DateTime::createFromFormat('Y-m', $toYear.'-'.sprintf('%02d', $toMonth));

            if ($fromDate > $toDate) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['target_deal' => 'Periode "dari" tidak boleh lebih besar dari periode "sampai"']);
            }

            // Handle file upload if new file is provided
            $documentPath = $prospect->document; // Keep existing document by default
            if ($request->hasFile('document')) {
                // Delete old document if exists
                if ($prospect->document && Storage::disk('public')->exists($prospect->document)) {
                    Storage::disk('public')->delete($prospect->document);
                }

                // Upload new document
                $file = $request->file('document');
                $fileName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
                $documentPath = $file->storeAs('prospects/documents', $fileName, 'public');
            }

            // Update data di database
            $prospect->update([
                'customer_name' => $validated['customer_name'],
                'no_handphone' => $validated['no_handphone'],
                'email' => $validated['email'],
                'company' => $validated['company'],
                'company_identity' => $validated['company_identity'],
                'pre_sales' => $validated['pre_sales'],
                'target_from_month' => $validated['target_deal_from_month'],
                'target_to_month' => $validated['target_deal_to_month'],
                'target_from_year' => $validated['target_deal_from_year'],
                'target_to_year' => $validated['target_deal_to_year'],
                'note' => $validated['note'],
                'document' => $documentPath,
            ]);

            return redirect()->route('prospect.index')
                ->with('success', 'Prospect berhasil diperbarui');

        } catch (\Exception $e) {
            // Jika terjadi error dan ada file baru yang diupload, hapus file tersebut
            if ($request->hasFile('document') && isset($documentPath) &&
                $documentPath !== $prospect->document &&
                Storage::disk('public')->exists($documentPath)) {
                Storage::disk('public')->delete($documentPath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui prospect: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $prospect = Prospect::with('prospectStatus')->findOrFail($id);

            // Prevent deleting if progress is 100%
            if (($prospect->prospectStatus->persentase ?? 0) >= 100) {
                return redirect()->route('prospect.index')
                    ->with('error', 'Prospect dengan progress 100% tidak dapat dihapus.');
            }

            // Check if prospect has quotations
            if ($prospect->quotations()->count() > 0) {
                return redirect()->route('prospect.index')
                    ->with('error', 'Tidak dapat menghapus prospect karena sudah memiliki quotation. Hapus quotation terlebih dahulu.');
            }

            // Delete associated document file if exists
            if ($prospect->document && Storage::disk('public')->exists($prospect->document)) {
                Storage::disk('public')->delete($prospect->document);
            }

            // Delete the prospect
            $prospect->delete();

            return redirect()->route('prospect.index')
                ->with('success', 'Prospect berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->route('prospect.index')
                ->with('error', 'Terjadi kesalahan saat menghapus prospect: '.$e->getMessage());
        }
    }
}
