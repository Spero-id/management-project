@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Create</div>
            <h2 class="page-title">Installation</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('installation.store') }}" method="POST" class="card" id="installationForm">
                @csrf
                <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

                <div class="card-header">
                    <h3 class="card-title">Installation Information</h3>
                </div>

                <div class="card-body">
                    <!-- quotation->prospect Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <div class="fw-bold">{{ $quotation->prospect->customer_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <div class="fw-bold">{{ $quotation->prospect->company ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Total Quantity Item </label>
                            <div class="input-group">
                                <p>{{ \App\Helpers\CurrencyHelper::formatRupiah($quotation->total_amount) }}</p>
                            </div>
                            @error('installation_percentage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Installation Percentage (%)</label>
                            <div class="input-group">
                                <input type="number" name="installation_percentage" id="installationPercentage"
                                    class="form-control" min="0" max="100" step="0.1"
                                    value="{{ old('installation_percentage', 0) }}">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('installation_percentage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- Notes -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                                placeholder="Enter installation notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Installations Section -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <label class="form-label">Installations</label>

                            <div class="table-responsive">
                                <table class="table table-vcenter" id="installationsTable">
                                    <thead>
                                        <tr>
                                            <th>Installation</th>
                                            <th width="120">Quantity</th>
                                            <th width="150">Unit Price</th>
                                            <th width="150">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="installationTableBody">
                                        @php
                                            // Load installation categories from controller-provided variable or fallback to model
                                            $installationCategories = $installationCategories ?? \App\Models\Installation::all()->map(function($i){
                                                return (object)[
                                                    'id' => $i->id,
                                                    'text' => $i->name ?? ($i->title ?? 'Installation'),
                                                    'proportional' => $i->proportional ?? null,
                                                ];
                                            });

                                            $initialIndex = 1;
                                            $installationPercentage = old('installation_percentage', 0);
                                            $productTotal = $quotation->total_amount ?? 0;
                                            $installationTotal = $productTotal * ($installationPercentage / 100);

                                            // Prepare allocations: respect item.proportional when present (>0), otherwise distribute remaining or evenly
                                            $items = $installationCategories->values();
                                            $serverRenderedCount = $items->count();

                                            $sumProportional = 0;
                                            foreach ($items as $it) {
                                                $p = isset($it->proportional) && $it->proportional !== null ? floatval($it->proportional) : 0;
                                                if ($p > 0) $sumProportional += $p;
                                            }

                                            $allocations = [];
                                            if ($serverRenderedCount > 0) {
                                                if ($sumProportional > 0) {
                                                    // First allocate all proportional items
                                                    $allocated = 0;
                                                    $unpropIndexes = [];
                                                    foreach ($items as $idx => $it) {
                                                        $p = isset($it->proportional) && $it->proportional !== null ? floatval($it->proportional) : 0;
                                                        if ($p > 0) {
                                                            $unit = $installationTotal * ($p / 100);
                                                            $allocations[$idx] = $unit;
                                                            $allocated += $unit;
                                                        } else {
                                                            $unpropIndexes[] = $idx;
                                                        }
                                                    }
                                                    $remaining = $installationTotal - $allocated;
                                                    $perUnprop = count($unpropIndexes) > 0 ? ($remaining / count($unpropIndexes)) : 0;
                                                    foreach ($unpropIndexes as $u) {
                                                        $allocations[$u] = $perUnprop;
                                                    }
                                                } else {
                                                    // Even distribution across all items
                                                    $perItem = $installationTotal / $serverRenderedCount;
                                                    foreach ($items as $idx => $it) {
                                                        $allocations[$idx] = $perItem;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @foreach($items as $idx => $item)
                                            @php
                                                $proportional = isset($item->proportional) && $item->proportional !== null ? floatval($item->proportional) : 0;
                                                $unitPrice = isset($allocations[$idx]) ? $allocations[$idx] : 0;
                                                $quantity = 0;
                                                $subtotal = $unitPrice * $quantity;
                                            @endphp
                                            <tr class="installation-row" @if($proportional > 0) data-proportional="{{ $proportional }}" @endif>
                                                <td>
                                                    <select readonly name="installations[{{ $initialIndex }}][installation_id]" class="form-select installation-select" required>
                                                        <option value="{{ $item->id }}" selected>{{ $item->text ?? $item->name ?? $item->title ?? 'Installation' }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="installations[{{ $initialIndex }}][quantity]" 
                                                           class="form-control installation-quantity-input" value="{{ $quantity }}" min="1" required>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input readonly type="text" name="installations[{{ $initialIndex }}][unit_price]" 
                                                               class="form-control installation-unit-price-input" value="{{ number_format(round($unitPrice)) }}" placeholder="0" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control installation-subtotal-display" 
                                                           value="{{ number_format(round($subtotal)) }}" readonly style="background-color: #f8f9fa;">
                                                </td>
                                                
                                            </tr>
                                            @php $initialIndex++; @endphp
                                        @endforeach

                                        @php
                                            // Expose server rendered count to JS directly
                                        @endphp
                                    </tbody>
                                </table>
                                <script>
                                    // Expose the server-rendered installation row count to JS
                                    window.__serverInstallationRowCount = {{ isset($serverRenderedCount) ? $serverRenderedCount : 0 }};
                                </script>
                            </div>

                            <!-- Empty State Message -->
                            <div id="emptyInstallationsState" class="text-center py-5 border rounded bg-light" @if(isset($serverRenderedCount) && $serverRenderedCount > 0) style="display:none;" @endif>

                          
                                <!-- Select All Installations Button -->
                                <button type="button" class="btn btn-secondary ms-2" id="selectAllInstallationsBtn" onclick="selectAllInstallations()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                        <path d="M9 11l3 3L22 4" />
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h11" />
                                    </svg>
                                    Select All Installations
                                </button>
                            </div>

                            <!-- Installation Total Section -->
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-lg">Installation Total:</strong>
                                                <strong class="text-lg text-success" id="installationTotalAmount">Rp
                                                    0</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('installations')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Installation Percentage -->

                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('installation.index') }}" class="btn btn-link">Cancel</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Create Installation</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .max-height-400 {
            max-height: 400px;
        }

        .product-checkbox {
            cursor: pointer;
            transform: scale(1.1);
        }

        .product-list-item {
            border: none !important;
            border-bottom: 1px solid #e9ecef !important;
            padding: 1rem 1.25rem !important;
            transition: all 0.2s ease-in-out;
        }

        .product-list-item:last-child {
            border-bottom: none !important;
        }

        .product-info {
            padding-left: 0.5rem;
        }

        .product-info .fw-semibold {
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .product-info .small {
            font-size: 0.8rem;
            line-height: 1.2;
        }

        .product-selection-modal .modal-body {
            padding: 1.5rem;
        }

        #productsList {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        #productsList .list-group {
            margin-bottom: 0;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .product-checkbox:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Loading and empty states */
        #productLoadingIndicator .product-list-item,
        #noProductsFound .product-list-item {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
        }

        #productLoadingIndicator .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        /* Search input styling */
        #productSearchInput {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease-in-out;
        }

        #productSearchInput:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        #searchProductBtn {
            border-radius: 0 0.5rem 0.5rem 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        /* Modal improvements */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        #selectedProductsCount {
            font-weight: 500;
            color: #6c757d;
        }

        /* Already added products styling */
        .product-list-item.bg-light {
            background-color: #f8f9fa !important;
            border-left: 3px solid #198754 !important;
        }

        .product-list-item.bg-light .product-checkbox:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .product-list-item.bg-light .product-info {
            opacity: 0.8;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function(){
            // Server-provided values
            window.__productTotal = {{ $quotation->total_amount ?? 0 }};

            function parseNumber(str){
                if (str === null || str === undefined) return 0;
                var s = String(str).replace(/[^0-9.-]+/g,'');
                return parseFloat(s) || 0;
            }

            function formatNumber(n){
                try{
                    return new Intl.NumberFormat('id-ID').format(Math.round(n));
                }catch(e){
                    return Math.round(n).toString();
                }
            }

            function recalcAll(){
                var pctEl = document.getElementById('installationPercentage');
                var percentage = parseFloat(pctEl ? pctEl.value : 0) || 0;
                var productTotal = window.__productTotal || 0;
                var installationTotal = productTotal * (percentage / 100);

                var rows = Array.from(document.querySelectorAll('.installation-row')) || [];
                if (rows.length === 0){
                    var installTotalEl = document.getElementById('installationTotalAmount');
                    if (installTotalEl) installTotalEl.textContent = 'Rp ' + formatNumber(0);
                    return;
                }

                var sumProportional = 0;
                rows.forEach(function(r){
                    var p = parseFloat(r.dataset.proportional) || 0;
                    if (p > 0) sumProportional += p;
                });

                var allocations = new Array(rows.length).fill(0);

                if (sumProportional > 0){
                    var allocated = 0;
                    var unpropIndexes = [];
                    rows.forEach(function(r, idx){
                        var p = parseFloat(r.dataset.proportional) || 0;
                        if (p > 0){
                            var unit = installationTotal * (p / 100);
                            allocations[idx] = unit;
                            allocated += unit;
                        } else {
                            unpropIndexes.push(idx);
                        }
                    });
                    var remaining = installationTotal - allocated;
                    var perUnprop = unpropIndexes.length > 0 ? (remaining / unpropIndexes.length) : 0;
                    unpropIndexes.forEach(function(i){ allocations[i] = perUnprop; });
                } else {
                    var perItem = installationTotal / rows.length;
                    for (var i=0;i<rows.length;i++) allocations[i] = perItem;
                }

                var displayedTotal = 0;
                rows.forEach(function(r, idx){
                    var qtyInput = r.querySelector('.installation-quantity-input');
                    var unitInput = r.querySelector('.installation-unit-price-input');
                    var subtotalDisplay = r.querySelector('.installation-subtotal-display');

                    var qty = parseNumber(qtyInput ? qtyInput.value : 0) ;
                    var unit = allocations[idx] || 0;

                    if (unitInput){
                        unitInput.value = formatNumber(unit);
                    }

                    var subtotal = unit * qty;
                    if (subtotalDisplay){
                        subtotalDisplay.value = formatNumber(subtotal);
                    }
                    displayedTotal += subtotal;
                });

                var installTotalEl = document.getElementById('installationTotalAmount');
                if (installTotalEl) installTotalEl.textContent = 'Rp ' + formatNumber(displayedTotal);
            }

            // Update subtotal when quantity changes (keep allocation but update subtotal and total)
            function attachQuantityListeners(){
                document.addEventListener('input', function(e){
                    if (e.target && e.target.classList && e.target.classList.contains('installation-quantity-input')){
                        // Recalculate all so total updates. This preserves allocations.
                        recalcAll();
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function(){
                // Attach listener to percentage input
                var pctEl = document.getElementById('installationPercentage');
                if (pctEl){
                    pctEl.addEventListener('input', function(){
                        recalcAll();
                    });
                }

                // Also attach listeners to quantity inputs
                attachQuantityListeners();

                // Run initial calculation to normalize server-rendered values and display totals
                recalcAll();
            });
        })();
    </script>
@endpush
