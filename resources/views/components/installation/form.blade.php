@props([
    'route' => null,
    'quotation' => null,
    'installation' => null,
    'installationCategories' => [],
    'accommodationCategory' => [],
    'accommodationItems' => [],
    'type' => 'edit',
])

<form action="{{ $route }}" method="POST" id="installationForm">
    @csrf
    @if ($installation)
        @method('PUT')
    @endif
    @if ($quotation)
        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
    @endif
    <input type="hidden" name="need_accommodation" id="need_accommodation_input"
        value="{{ old('need_accommodation') ? 1 : 0 }}">
    <input type="hidden" name="form_type" value="{{ $type }}">



    <div>


        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Total Quantity Item </label>
                <div class="input-group">

                    <input class="form-control" disabled value="{{ \App\Helpers\CurrencyHelper::formatRupiah($quotation->total_amount) }}">
                </div>

            </div>
            <div class="col-md-6">
                <label class="form-label">Installation Percentage (%)</label>
                <div class="input-group">
                    <input type="number" name="installation_percentage" id="installationPercentage"
                        class="form-control" min="0" max="100" step="0.1"
                        value="{{ old('installation_percentage', $quotation->installation_percentage ?? 0) }}">
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
                    placeholder="Enter installation notes">{{ old('notes', $quotation->notes) }}</textarea>
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
                                $items =
                                    $installationCategories ??
                                    \App\Models\Installation::all()->map(function ($i) {
                                        return (object) [
                                            'id' => $i->id,
                                            'text' => $i->name ?? ($i->title ?? 'Installation'),
                                            'proportional' => $i->proportional ?? null,
                                        ];
                                    });

                            @endphp

                            @if ($installation != null)
                                @foreach ($installation as $idx => $item)
                                    <tr class="installation-row">
                                        <td>
                                            <select readonly name="installations[{{ $idx }}][installation_id]"
                                                class="form-select installation-select" required>
                                                <option value="{{ $item->installation->id }}" selected>
                                                    {{ $item->installation->name }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>

                                            <input type="number" name="installations[{{ $idx }}][quantity]"
                                                class="form-control installation-quantity-input"
                                                value="{{ $item->quantity }}" min="1" required>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input readonly type="text"
                                                    name="installations[{{ $idx }}][unit_price]"
                                                    class="form-control installation-unit-price-input"
                                                    value="{{ old('installations.' . $idx . '.unit_price', number_format($item->unit_price, 0, ',', '.')) }}"
                                                    placeholder="0" required>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control installation-subtotal-display"
                                                value="{{ number_format($item->subtotal, 0, ',', '.') }}" readonly
                                                style="background-color: #f8f9fa;">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $initialIndex = 1;
                                    $installationPercentage = old(
                                        'installation_percentage',
                                        $quotation?->installation_percentage ?? 0,
                                    );
                                    $productTotal = $quotation?->total_amount ?? 0;
                                    $installationTotal = $productTotal * ($installationPercentage / 100);

                                    $serverRenderedCount = $items->count();

                                    $sumProportional = 0;
                                    foreach ($items as $it) {
                                        $p =
                                            isset($it->proportional) && $it->proportional !== null
                                                ? floatval($it->proportional)
                                                : 0;
                                        if ($p > 0) {
                                            $sumProportional += $p;
                                        }
                                    }

                                    $allocations = [];
                                    if ($serverRenderedCount > 0) {
                                        if ($sumProportional > 0) {
                                            $allocated = 0;
                                            $unpropIndexes = [];
                                            foreach ($items as $idx => $it) {
                                                $p =
                                                    isset($it->proportional) && $it->proportional !== null
                                                        ? floatval($it->proportional)
                                                        : 0;
                                                if ($p > 0) {
                                                    $unit = $installationTotal * ($p / 100);
                                                    $allocations[$idx] = $unit;
                                                    $allocated += $unit;
                                                } else {
                                                    $unpropIndexes[] = $idx;
                                                }
                                            }
                                            $remaining = $installationTotal - $allocated;
                                            $perUnprop =
                                                count($unpropIndexes) > 0 ? $remaining / count($unpropIndexes) : 0;
                                            foreach ($unpropIndexes as $u) {
                                                $allocations[$u] = $perUnprop;
                                            }
                                        } else {
                                            $perItem = $installationTotal / $serverRenderedCount;
                                            foreach ($items as $idx => $it) {
                                                $allocations[$idx] = $perItem;
                                            }
                                        }
                                    }
                                @endphp

                                @foreach ($items as $idx => $item)
                                    @php
                                        $proportional =
                                            isset($item->proportional) && $item->proportional !== null
                                                ? floatval($item->proportional)
                                                : 0;
                                        $unitPrice = isset($allocations[$idx]) ? $allocations[$idx] : 0;
                                        $quantity = 0;
                                        $subtotal = $unitPrice * $quantity;
                                    @endphp
                                    <tr class="installation-row"
                                        @if ($proportional > 0) data-proportional="{{ $proportional }}" @endif>
                                        <td>
                                            <select readonly name="installations[{{ $initialIndex }}][installation_id]"
                                                class="form-select installation-select" required>
                                                <option value="{{ $item->id }}" selected>
                                                    {{ $item->text ?? ($item->name ?? ($item->title ?? 'Installation')) }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="installations[{{ $initialIndex }}][quantity]"
                                                class="form-control installation-quantity-input"
                                                value="{{ $quantity }}" min="1" required>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input readonly type="text"
                                                    name="installations[{{ $initialIndex }}][unit_price]"
                                                    class="form-control installation-unit-price-input"
                                                    value="{{ number_format(round($unitPrice)) }}" placeholder="0"
                                                    required>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control installation-subtotal-display"
                                                value="{{ number_format(round($subtotal)) }}" readonly
                                                style="background-color: #f8f9fa;">
                                        </td>
                                    </tr>
                                    @php $initialIndex++; @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <script>
                        window.__serverInstallationRowCount = {{ isset($serverRenderedCount) ? $serverRenderedCount : 0 }};
                    </script>
                </div>


                <!-- Accommodation Section -->
                <div class="row mt-5 mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Accommodation </h4>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="need_accommodation" class="form-check-input"
                                        id="accommodationToggle" @if (old('need_accommodation', $quotation?->need_accommodation)) checked @endif>

                                </div>
                            </div>

                            <div class="card-body" id="accommodationFormContainer"
                                style="@if (!old('need_accommodation', $quotation?->need_accommodation)) display: none; @endif">
                                <!-- Accommodation Details -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Wilayah / AREA</label>
                                        <input type="text" id="accommodation_wilayah" name="accommodation_wilayah"
                                            class="form-control @error('accommodation_wilayah') is-invalid @enderror"
                                            placeholder="Enter area"
                                            value="{{ old('accommodation_wilayah', $quotation?->accommodation_wilayah) }}">
                                        @error('accommodation_wilayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Kamar Hotel</label>
                                        <input type="number" id="accommodation_rooms"
                                            name="accommodation_hotel_rooms"
                                            class="form-control @error('accommodation_hotel_rooms') is-invalid @enderror"
                                            placeholder="Enter number of rooms"
                                            value="{{ old('accommodation_hotel_rooms', $quotation?->accommodation_hotel_rooms ?? 0) }}"
                                            readonly>
                                        @error('accommodation_hotel_rooms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Orang</label>
                                        <input type="number" name="accommodation_people" id="accommodation_people"
                                            class="form-control @error('accommodation_people') is-invalid @enderror"
                                            placeholder="Enter number of people"
                                            value="{{ old('accommodation_people', $quotation?->accommodation_people ?? 0) }}">
                                        @error('accommodation_people')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target Pekerjaan (Hari)</label>
                                        <input type="number" id="accommodation_target_days"
                                            name="accommodation_target_days"
                                            class="form-control @error('accommodation_target_days') is-invalid @enderror"
                                            placeholder="Enter target days"
                                            value="{{ old('accommodation_target_days', $quotation?->accommodation_target_days ?? 0) }}">
                                        @error('accommodation_target_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Harga Tiket Pesawat (per orang)</label>
                                        <input type="number" id="accommodation_ticket_price"
                                            name="accommodation_plane_ticket_price"
                                            class="form-control @error('accommodation_plane_ticket_price') is-invalid @enderror"
                                            placeholder="Enter ticket price"
                                            value="{{ old('accommodation_plane_ticket_price', $quotation?->accommodation_plane_ticket_price ?? 0) }}">
                                        @error('accommodation_plane_ticket_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Accommodation Cost Table -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">Accommodation Costs</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th width="200">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    <tr>
                                                        <td>Total Harga Hotel</td>
                                                        <td>
                                                            <input type="text" name="total_hotel_price"
                                                                id="total_hotel_price_input"
                                                                class="form-control @error('total_hotel_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Total Harga Hotel')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_hotel_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Harga Pesawat</td>
                                                        <td>
                                                            <input type="text" name="total_flight_price"
                                                                id="flight_price_input"
                                                                class="form-control @error('total_flight_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Harga Pesawat')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_flight_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Harga Transportasi Kendaraan</td>
                                                        <td>
                                                            <input type="text" name="total_transportation_price"
                                                                id="total_transportation_price"
                                                                class="form-control @error('total_transportation_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Harga Transportasi Kendaraan')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_transportation_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Installation Total Section -->
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-lg">Installation Total:</strong>
                                    <strong class="text-lg text-success" id="installationTotalAmount">Rp 0</strong>
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


    </div>

    <div class="mt-4">
        <div class="d-flex justify-content-end">

            <div>
                <button type="submit" class="btn btn-primary">Simpan Installation</button>
            </div>
        </div>
    </div>
</form>



@pushOnce('styles')
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
@endPushOnce

@pushOnce('scripts')
    <script>
        (function() {
            window.__productTotal = {{ $quotation->total_amount ?? 0 }};

            function parseNumber(str) {
                if (str === null || str === undefined) return 0;
                var s = String(str).replace(/[^0-9.-]+/g, '');
                return parseFloat(s) || 0;
            }

            function formatNumber(n) {
                try {
                    return new Intl.NumberFormat('id-ID').format(Math.round(n));
                } catch (e) {
                    return Math.round(n).toString();
                }
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            function parseRupiah(rupiahString) {
                return parseFloat(rupiahString.replace(/[.,]/g, '')) || 0;
            }

            // ===== ACCOMMODATION FUNCTIONS =====
            function safeAttachAccommodationListeners() {
                const targetDaysEl = document.getElementById('accommodation_target_days');
                const peopleEl = document.getElementById('accommodation_people');
                const ticketEl = document.getElementById('accommodation_ticket_price');

                if (targetDaysEl) {
                    targetDaysEl.addEventListener('input', calculateHotelPrice);
                }
                if (peopleEl) {
                    peopleEl.addEventListener('input', function() {
                        calculateTicketPrice();
                        calculateTransportationPrice();
                        calculateHotelPrice();
                    });
                }
                if (ticketEl) {
                    ticketEl.addEventListener('input', calculateTicketPrice);
                }
            }

            function calculateHotelPrice() {
                const peopleEl = document.getElementById('accommodation_people');
                const daysEl = document.getElementById('accommodation_target_days');
                if (!peopleEl || !daysEl) return;

                const people = parseInt(peopleEl.value) || 0;
                const days = parseInt(daysEl.value) || 0;
                const rooms = Math.ceil(people / 2);

                const roomInput = document.getElementById('accommodation_rooms') || document.querySelector(
                    'input[name="accommodation_hotel_rooms"]');
                if (roomInput) roomInput.value = rooms;

                if (rooms > 0 && days > 0) {
                    const hotelPrice = {{ $accommodationCategory[0]->price ?? 0 }} * days * rooms;
                    console.log({{ $accommodationCategory[0]->price ?? 0 }}, days, rooms);

                    const hotelInput = document.getElementById('total_hotel_price_input') || document.querySelector(
                        'input[name="total_hotel_price"]');

                    if (hotelInput) hotelInput.value = formatRupiah(hotelPrice);

                    // const summaryHotel = document.getElementById('summaryHotel');
                    // if (summaryHotel) summaryHotel.textContent = formatRupiah(hotelPrice);
                }
            }

            function calculateTicketPrice() {
                const peopleEl = document.getElementById('accommodation_people');
                const ticketEl = document.getElementById('accommodation_ticket_price');
                if (!peopleEl || !ticketEl) return;

                const people = parseInt(peopleEl.value) || 0;
                const ticket = parseInt(ticketEl.value) || 0;

                if (people > 0 && ticket > 0) {
                    const TicketPrice = ticket * people * 2;

                    const flightInput = document.getElementById('flight_price_input');
                    if (flightInput) flightInput.value = formatRupiah(TicketPrice);

                    console.log(flightInput);

                    // const summaryFlight = document.getElementById('summaryFlight');
                    // if (summaryFlight) summaryFlight.textContent = formatRupiah(TicketPrice);
                }
            }

            function calculateTransportationPrice() {
                const peopleEl = document.getElementById('accommodation_people');
                if (!peopleEl) return;

                const people = parseInt(peopleEl.value) || 0;

                if (people > 0) {
                    const transportationPrice = {{ $accommodationCategory[1]->price ?? 0 }} * people;

                    const transportationInput = document.getElementById('total_transportation_price');
                    console.log(transportationInput);
                    if (transportationInput) transportationInput.value = formatRupiah(transportationPrice);

                }
            }

            function recalcAll() {
                var pctEl = document.getElementById('installationPercentage');
                var percentage = parseFloat(pctEl ? pctEl.value : 0) || 0;
                var productTotal = window.__productTotal || 0;
                var installationTotal = productTotal * (percentage / 100);

                var rows = Array.from(document.querySelectorAll('.installation-row')) || [];
                if (rows.length === 0) {
                    var installTotalEl = document.getElementById('installationTotalAmount');
                    if (installTotalEl) installTotalEl.textContent = 'Rp ' + formatNumber(0);
                    return;
                }

                var sumProportional = 0;
                rows.forEach(function(r) {
                    var p = parseFloat(r.dataset.proportional) || 0;
                    if (p > 0) sumProportional += p;
                });

                var allocations = new Array(rows.length).fill(0);

                if (sumProportional > 0) {
                    var allocated = 0;
                    var unpropIndexes = [];
                    rows.forEach(function(r, idx) {
                        var p = parseFloat(r.dataset.proportional) || 0;
                        if (p > 0) {
                            var unit = installationTotal * (p / 100);
                            allocations[idx] = unit;
                            allocated += unit;
                        } else {
                            unpropIndexes.push(idx);
                        }
                    });
                    var remaining = installationTotal - allocated;
                    var perUnprop = unpropIndexes.length > 0 ? (remaining / unpropIndexes.length) : 0;
                    unpropIndexes.forEach(function(i) {
                        allocations[i] = perUnprop;
                    });
                } else {
                    var perItem = installationTotal / rows.length;
                    for (var i = 0; i < rows.length; i++) allocations[i] = perItem;
                }

                var displayedTotal = 0;
                rows.forEach(function(r, idx) {
                    var qtyInput = r.querySelector('.installation-quantity-input');
                    var unitInput = r.querySelector('.installation-unit-price-input');
                    var subtotalDisplay = r.querySelector('.installation-subtotal-display');

                    var qty = parseNumber(qtyInput ? qtyInput.value : 0);
                    var unit = allocations[idx] || 0;

                    if (unitInput) {
                        unitInput.value = formatNumber(unit);
                    }

                    var subtotal = unit * qty;
                    if (subtotalDisplay) {
                        subtotalDisplay.value = formatNumber(subtotal);
                    }
                    displayedTotal += subtotal;
                });

                var installTotalEl = document.getElementById('installationTotalAmount');
                if (installTotalEl) installTotalEl.textContent = 'Rp ' + formatNumber(displayedTotal);
            }

            function attachQuantityListeners() {
                document.addEventListener('input', function(e) {
                    if (e.target && e.target.classList && e.target.classList.contains(
                            'installation-quantity-input')) {
                        recalcAll();
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                var pctEl = document.getElementById('installationPercentage');
                if (pctEl) {
                    pctEl.addEventListener('input', function() {
                        recalcAll();
                    });
                }

                attachQuantityListeners();
                recalcAll();

                // Accommodation toggle listeners
                const accommodationToggle = document.getElementById('accommodationToggle');
                const accommodationFormContainer = document.getElementById('accommodationFormContainer');
                const needAccommodationInput = document.getElementById('need_accommodation_input');

                if (accommodationToggle) {
                    accommodationToggle.addEventListener('change', function() {
                        if (this.checked) {
                            accommodationFormContainer.style.display = 'block';
                            needAccommodationInput.value = '1';
                        } else {
                            accommodationFormContainer.style.display = 'none';
                            needAccommodationInput.value = '0';
                        }
                    });
                }

                // Attach accommodation field listeners
                safeAttachAccommodationListeners();
            });
        })
        ();
    </script>
@endPushOnce
