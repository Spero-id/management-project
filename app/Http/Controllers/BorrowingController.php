<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BorrowingController extends Controller
{
    public function index()
    {
        return view('borrowing.index');
    }

    public function datatable()
    {
        $borrowings = Borrowing::query()->select(['id', 'no_peminjaman', 'penanggung_jawab', 'keperluan', 'tanggal', 'status']);

        return DataTables::eloquent($borrowings)
            ->editColumn('tanggal', function ($row) {
                return $row->tanggal ? $row->tanggal->format('d/m/Y') : '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status === 'returned') {
                    return '<span class="badge bg-success text-white">Returned</span>';
                } elseif ($row->status === 'outstanding') {
                    return '<span class="badge bg-orange text-white">Outstanding</span>';
                }

                return '<span class="badge bg-warning text-white">Borrowed</span>';
            })
            ->addColumn('action', function ($row) {
             

                $editBtn = '<button type="button" class="btn btn-icon edit-btn" data-id="'.$row['id'].'" aria-label="Edit" title="Edit borrowing">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                </button>';

                $deleteBtn = '<button type="button" class="btn btn-icon delete-btn" data-id="'.$row['id'].'" aria-label="Delete borrowing">
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
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $lastBorrowing = Borrowing::latest('id')->first();
        $number = $lastBorrowing ? (int) substr($lastBorrowing->no_peminjaman, 4, 3) + 1 : 1;
        $noPeminjaman = 'FKB/'.str_pad($number, 3, '0', STR_PAD_LEFT).'/'.strtoupper(date('M')).'/'.date('Y');

        return response()->json([
            'no_peminjaman' => $noPeminjaman,
            'tanggal' => date('Y-m-d'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_peminjaman' => 'required|string|max:255|unique:borrowings,no_peminjaman',
            'keperluan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.brand' => 'required|string|max:255',
            'items.*.type' => 'required|string|max:255',
            'items.*.stok_tersedia' => 'nullable|integer|min:0',
            'items.*.jumlah_barang' => 'required|integer|min:1',
        ]);

        $borrowing = Borrowing::create([
            'tanggal' => $validated['tanggal'],
            'no_peminjaman' => $validated['no_peminjaman'],
            'keperluan' => $validated['keperluan'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'status' => 'borrowed',
        ]);

        foreach ($validated['items'] as $item) {
            $borrowing->items()->create([
                'brand' => $item['brand'],
                'type' => $item['type'],
                'stok_tersedia' => $item['stok_tersedia'] ?? null,
                'jumlah_barang' => $item['jumlah_barang'],
            ]);

            // Reduce stock
            $product = Product::where('brand', $item['brand'])
                ->where('type', $item['type'])
                ->first();

            if ($product && $product->stock) {
                $product->stock->decrement('stock_quantity', $item['jumlah_barang']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Borrowing created successfully',
            'data' => $borrowing->load('items'),
        ]);
    }

    public function show(string $id)
    {
        $borrowing = Borrowing::with('items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $borrowing,
        ]);
    }

    public function edit(string $id)
    {
        $borrowing = Borrowing::with('items')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $borrowing,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_peminjaman' => 'required|string|max:255|unique:borrowings,no_peminjaman,'.$id,
            'keperluan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.brand' => 'required|string|max:255',
            'items.*.type' => 'required|string|max:255',
            'items.*.stok_tersedia' => 'nullable|integer|min:0',
            'items.*.jumlah_barang' => 'required|integer|min:1',
        ]);

        // Restore stock from old items
        foreach ($borrowing->items as $oldItem) {
            $product = Product::where('brand', $oldItem->brand)
                ->where('type', $oldItem->type)
                ->first();

            if ($product && $product->stock) {
                $product->stock->increment('stock_quantity', $oldItem->jumlah_barang);
            }
        }

        $borrowing->update([
            'tanggal' => $validated['tanggal'],
            'no_peminjaman' => $validated['no_peminjaman'],
            'keperluan' => $validated['keperluan'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
        ]);

        $borrowing->items()->delete();
        foreach ($validated['items'] as $item) {
            $borrowing->items()->create([
                'brand' => $item['brand'],
                'type' => $item['type'],
                'stok_tersedia' => $item['stok_tersedia'] ?? null,
                'jumlah_barang' => $item['jumlah_barang'],
            ]);

            // Reduce stock for new items
            $product = Product::where('brand', $item['brand'])
                ->where('type', $item['type'])
                ->first();

            if ($product && $product->stock) {
                $product->stock->decrement('stock_quantity', $item['jumlah_barang']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Borrowing updated successfully',
            'data' => $borrowing->load('items'),
        ]);
    }

    public function destroy(string $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Restore stock for all items
        foreach ($borrowing->items as $item) {
            $product = Product::where('brand', $item->brand)
                ->where('type', $item->type)
                ->first();

            if ($product && $product->stock) {
                $product->stock->increment('stock_quantity', $item->jumlah_barang);
            }
        }

        $borrowing->items()->delete();
        $borrowing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Borrowing deleted successfully',
        ]);
    }

    public function getUsers(Request $request)
    {
        $search = $request->get('search', '');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('no_karyawan', 'like', "%{$search}%");
        })
            ->select('id', 'name', 'no_karyawan')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->name,
                    'text' => $user->name.' ('.$user->no_karyawan.')',
                ];
            });

        return response()->json($users);
    }

    public function getBrands(Request $request)
    {
        $search = $request->get('search', '');

        $brands = Product::when($search, function ($query, $search) {
            return $query->where('brand', 'like', "%{$search}%");
        })
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->take(10);

        return response()->json($brands);
    }

    public function getTypes(Request $request)
    {
        $brand = $request->get('brand');
        $search = $request->get('search', '');

        $types = Product::when($brand, function ($query, $brand) {
            return $query->where('brand', $brand);
        })
            ->when($search, function ($query, $search) {
                return $query->where('type', 'like', "%{$search}%");
            })
            ->whereNotNull('type')
            ->distinct()
            ->pluck('type')
            ->take(10);

        return response()->json($types);
    }

    public function getCurrentStock(Request $request)
    {
        $brand = $request->get('brand');
        $type = $request->get('type');

        if (! $brand || ! $type) {
            return response()->json(['stock' => 0]);
        }

        $product = Product::where('brand', $brand)
            ->where('type', $type)
            ->first();

        if (! $product) {
            return response()->json(['stock' => 0]);
        }

        $stock = ProductStock::where('product_id', $product->id)->first();

        return response()->json([
            'stock' => $stock ? $stock->stock_quantity : 0,
        ]);
    }

    public function getBorrowedItems(Request $request)
    {
        $search = $request->get('search', '');

        $borrowings = Borrowing::with('items')
            ->whereIn('status', ['borrowed', 'outstanding'])
            ->when($search, function ($query, $search) {
                return $query->where('no_peminjaman', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            })
            ->get()
            ->map(function ($borrowing) {
                $unreturned_items = $borrowing->items->filter(function ($item) {
                    return $item->jumlah_dikembalikan < $item->jumlah_barang;
                })->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'brand' => $item->brand,
                        'type' => $item->type,
                        'stok_tersedia' => $item->stok_tersedia,
                        'jumlah_barang' => $item->jumlah_barang - $item->jumlah_dikembalikan, // Sisa yang belum dikembalikan
                        'jumlah_dikembalikan' => $item->jumlah_dikembalikan,
                    ];
                })->values();

                return [
                    'id' => $borrowing->id,
                    'text' => $borrowing->no_peminjaman.' - '.$borrowing->penanggung_jawab,
                    'tanggal' => $borrowing->tanggal->format('Y-m-d'),
                    'no_peminjaman' => $borrowing->no_peminjaman,
                    'penanggung_jawab' => $borrowing->penanggung_jawab,
                    'items' => $unreturned_items,
                ];
            })
            ->filter(function ($borrowing) {
                // Hanya show borrowing yang masih ada item belum dikembalikan
                return $borrowing['items']->count() > 0;
            })
            ->values();

        return response()->json($borrowings);
    }

    public function getBorrowingDetail(string $id)
    {
        $borrowing = Borrowing::with('items')->findOrFail($id);

        // Filter items yang belum fully returned
        $unreturned_items = $borrowing->items->filter(function ($item) {
            return $item->jumlah_dikembalikan < $item->jumlah_barang;
        })->map(function ($item) {
            return [
                'id' => $item->id,
                'brand' => $item->brand,
                'type' => $item->type,
                'stok_tersedia' => $item->stok_tersedia,
                'jumlah_sudah_dikembalikan' => $item->jumlah_dikembalikan,
                'jumlah_barang' => $item->jumlah_barang - $item->jumlah_dikembalikan, // Sisa yang belum dikembalikan
                'jumlah_dikembalikan' => $item->jumlah_barang - $item->jumlah_dikembalikan, // Default to full return
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $borrowing->id,
                'no_peminjaman' => $borrowing->no_peminjaman,
                'penanggung_jawab' => $borrowing->penanggung_jawab,
                'tanggal' => $borrowing->tanggal->format('Y-m-d'),
                'items' => $unreturned_items,
            ],
        ]);
    }

    public function returnItems(Request $request)
    {
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'tanggal_pengembalian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:borrowing_items,id',
            'items.*.jumlah_dikembalikan' => 'required|integer|min:1',
        ]);

        $borrowing = Borrowing::with('items')->findOrFail($validated['borrowing_id']);
        
        $allItemsFullyReturned = true;

        foreach ($validated['items'] as $returnItem) {
            $borrowingItem = $borrowing->items->firstWhere('id', $returnItem['id']);

            if (! $borrowingItem) {
                continue;
            }

            // Validasi jumlah yang dikembalikan tidak melebihi sisa yang belum dikembalikan
            $sisaBelumKembali = $borrowingItem->jumlah_barang - $borrowingItem->jumlah_dikembalikan;
            if ($returnItem['jumlah_dikembalikan'] > $sisaBelumKembali) {
                return response()->json([
                    'success' => false,
                    'message' => "Jumlah pengembalian untuk {$borrowingItem->brand} {$borrowingItem->type} melebihi sisa yang dipinjam ({$sisaBelumKembali})",
                ], 422);
            }

            // Restore stock
            $product = Product::where('brand', $borrowingItem->brand)
                ->where('type', $borrowingItem->type)
                ->first();

            if ($product && $product->stock) {
                $product->stock->increment('stock_quantity', $returnItem['jumlah_dikembalikan']);
            }

            // Update jumlah_dikembalikan
            $borrowingItem->increment('jumlah_dikembalikan', $returnItem['jumlah_dikembalikan']);
            
            // Check if item is fully returned
            if ($borrowingItem->jumlah_dikembalikan < $borrowingItem->jumlah_barang) {
                $allItemsFullyReturned = false;
            }
        }

        // Update status berdasarkan kondisi pengembalian
        if ($allItemsFullyReturned) {
            $borrowing->update([
                'status' => 'returned',
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
            ]);
        } else {
            $borrowing->update([
                'status' => 'outstanding',
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Items returned successfully',
        ]);
    }
}
