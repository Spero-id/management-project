@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Quotation Details</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('quotation.pdf', $quotation->id) }}" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                        <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                        <path d="M17 18h2" />
                        <path d="M20 15h-3v6" />
                        <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                    </svg>
                    Download 
                </a>
                <a href="{{ route('quotation.edit', $quotation->id) }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Edit 
                </a>
                <form action="{{ route('quotation.destroy', $quotation->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete this quotation? This will also delete all associated quotation items and cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                        Delete 
                    </button>
                </form>
                <a href="{{ route('prospect.show', $quotation->prospect->id) }}" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Prospect
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Quotation Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Quotation Number -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Quotation Number</label>
                                <div class="fw-bold">{{ $quotation->quotation_number }}</div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-secondary',
                                            'sent' => 'bg-info',
                                            'accepted' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                        ];
                                        $statusColor = $statusColors[$quotation->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusColor }} text-white">
                                        {{ ucfirst($quotation->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Customer</label>
                                <div class="fw-bold">{{ $quotation->prospect->customer_name }}</div>
                            </div>
                        </div>

                        <!-- Company -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Company</label>
                                <div class="fw-bold">{{ $quotation->prospect->company ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Amount</label>
                                <div class="fw-bold text-success fs-4">
                                    Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created Date</label>
                                <div class="fw-bold">
                                    {{ $quotation->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if ($quotation->notes)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Notes</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {{ $quotation->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quotation Items Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M3 3h18v4H3z"></path>
                            <path d="M3 11h18v4H3z"></path>
                            <path d="M3 19h18v4H3z"></path>
                        </svg>
                        Quotation Items
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotation->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $item->product->name }}</div>
                                            @if ($item->product->description)
                                                <div class="text-muted small">{{ $item->product->description }}</div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->quantity) }}</td>
                                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="empty">
                                                <p class="empty-title">No items found</p>
                                                <p class="empty-subtitle text-muted">
                                                    This quotation doesn't have any items yet.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($quotation->items->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Products Total:</td>
                                        <td class="text-end fw-bold text-success fs-5">
                                            Rp {{ number_format($quotation->items->sum('subtotal'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Installation Items Section -->
    @if($quotation->installationItems->count() > 0)
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"></path>
                            <path d="M12 12l8-4.5"></path>
                            <path d="M12 12v9"></path>
                            <path d="M12 12L4 7.5"></path>
                        </svg>
                        Installation Items
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Installation Service</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->installationItems as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $item->installation->name }}</div>
                                            @if ($item->installation->description)
                                                <div class="text-muted small">{{ $item->installation->description }}</div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->quantity) }}</td>
                                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Installation Total:</td>
                                    <td class="text-end fw-bold text-info fs-5">
                                        Rp {{ number_format($quotation->installationItems->sum('subtotal'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($quotation->need_accommodation)
    <!-- Accommodation Items Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M3 3h18v4H3z"></path>
                            <path d="M3 11h18v4H3z"></path>
                            <path d="M3 19h18v4H3z"></path>
                        </svg>
                        Accommodation Items
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Wilayah</label>
                                <div class="fw-bold">{{ $quotation->accommodation_wilayah }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Hotel Rooms</label>
                                <div class="fw-bold">{{ $quotation->accommodation_hotel_rooms }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">People</label>
                                <div class="fw-bold">{{ $quotation->accommodation_people }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Target Days</label>
                                <div class="fw-bold">{{ $quotation->accommodation_target_days }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Plane Ticket Price</label>
                                <div class="fw-bold">Rp {{ number_format($quotation->accommodation_plane_ticket_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Accommodation</th>
                                    <th>Unit Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->accommodationItems as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $item->name }}</div>
                                        </td>
                                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1" class="text-end fw-bold">Accommodation Total:</td>
                                    <td class="text-end fw-bold text-warning fs-5">
                                        Rp {{ number_format($quotation->accommodation_total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
